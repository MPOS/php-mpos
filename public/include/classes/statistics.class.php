<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');


/*
 * We give access to plenty of statistics through this class
 * Statistics should be non-intrusive and not change any
 * rows in our database to ensure data integrity for the backend
 **/
class Statistics {
  private $sError = '';
  private $table = 'statistics_shares';
  private $getcache = true;

  public function __construct($debug, $mysqli, $config, $share, $user, $block, $memcache) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->share = $share;
    $this->config = $config;
    $this->user = $user;
    $this->block = $block;
    $this->memcache = $memcache;
    $this->debug->append("Instantiated Share class", 2);
  }

  /* Some basic get and set methods
   **/
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }

  // Disable fetching values from cache
  public function setGetCache($set=false) {
    $this->getcache = $set;
  }
  public function getGetCache() {
    return $this->getcache;
  }

  private function checkStmt($bState) {
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Failed to prepare statement');
      return false;
    }
    return true;
  }

  /**
   * Get our last $limit blocks found
   * @param limit int Last limit blocks
   * @return array
   **/
  public function getBlocksFound($limit=10) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__ . $limit)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT
        b.*,
        a.username AS finder,
        a.is_anonymous AS is_anonymous
      FROM " . $this->block->getTableName() . " AS b
      LEFT JOIN " . $this->user->getTableName() . " AS a
      ON b.account_id = a.id
      ORDER BY height DESC LIMIT ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param("i", $limit) && $stmt->execute() && $result = $stmt->get_result())
      return $this->memcache->setCache(__FUNCTION__ . $limit, $result->fetch_all(MYSQLI_ASSOC), 5);
    // Catchall
    $this->debug->append("Failed to find blocks:" . $this->mysqli->error);
    return false;
  }

  /**
   * Currently the only function writing to the database
   * Stored per block user statistics of valid and invalid shares
   * @param aStats array Array with user id, valid and invalid shares
   * @param iBlockId int Block ID as store in the Block table
   * @return bool
   **/
  public function updateShareStatistics($aStats, $iBlockId) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id, valid, invalid, block_id) VALUES (?, ?, ?, ?)");
    if ($this->checkStmt($stmt) && $stmt->bind_param('iiii', $aStats['id'], $aStats['valid'], $aStats['invalid'], $iBlockId) && $stmt->execute()) return true;
    // Catchall
    $this->debug->append("Failed to update share stats: " . $this->mysqli->error);
    return false;
  }

  /**
   * Get our current pool hashrate for the past 10 minutes across both
   * shares and shares_archive table
   * @param none
   * @return data object Return our hashrateas an object
   **/
  public function getCurrentHashrate() {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($this->getGetCache() && $data = $this->memcache->get(__FUNCTION__)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT
      (
        (
          SELECT IFNULL(ROUND(SUM(IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty)) * 65536/600/1000), 0) AS hashrate
          FROM " . $this->share->getTableName() . "
          WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE)
        ) + (
          SELECT IFNULL(ROUND(SUM(IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty)) * 65536/600/1000), 0) AS hashrate
          FROM " . $this->share->getArchiveTableName() . "
          WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE)
        )
      ) AS hashrate
      FROM DUAL");
    // Catchall
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result() ) return $this->memcache->setCache(__FUNCTION__, $result->fetch_object()->hashrate);
    $this->debug->append("Failed to get hashrate: " . $this->mysqli->error);
    return false;
  }

  /**
   * Same as getCurrentHashrate but for Shares
   * @param none
   * @return data object Our share rate in shares per second
   **/
  public function getCurrentShareRate() {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT ROUND(SUM(IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty)) / 600, 2) AS sharerate
      FROM " . $this->share->getTableName() . "
      WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE)");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result() ) return $this->memcache->setCache(__FUNCTION__, $result->fetch_object()->sharerate);
    // Catchall
    $this->debug->append("Failed to fetch share rate: " . $this->mysqli->error);
    return false;
  }

  /**
   * Get total shares for this round, since last block found
   * @param none
   * @return data array invalid and valid shares
   **/
  public function getRoundShares() {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($this->getGetCache() && $data = $this->memcache->get(__FUNCTION__)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT
        IFNULL(SUM(IF(our_result='Y', IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty), 0)), 0) AS valid,
        IFNULL(SUM(IF(our_result='N', IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty), 0)), 0) AS invalid
      FROM " . $this->share->getTableName() . "
      WHERE UNIX_TIMESTAMP(time) >IFNULL((SELECT MAX(time) FROM " . $this->block->getTableName() . "),0)");
    if ( $this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result() )
      return $this->memcache->setCache(__FUNCTION__, $result->fetch_assoc());
    // Catchall
    $this->debug->append("Failed to fetch round shares: " . $this->mysqli->error);
    return false;
  }

  /**
   * Get amount of shares for a all users
   * Used in statistics cron to refresh memcache data
   * @param account_id int User ID
   * @return data array invalid and valid share counts
   **/
  public function getAllUserShares() {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($this->getGetCache() && $data = $this->memcache->get(__FUNCTION__)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT
        IFNULL(SUM(IF(our_result='Y', IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty), 0)), 0) AS valid,
        IFNULL(SUM(IF(our_result='N', IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty), 0)), 0) AS invalid,
        u.id AS id,
        u.username AS username
      FROM " . $this->share->getTableName() . " AS s,
           " . $this->user->getTableName() . " AS u
      WHERE u.username = SUBSTRING_INDEX( s.username, '.', 1 )
        AND UNIX_TIMESTAMP(s.time) >IFNULL((SELECT MAX(b.time) FROM " . $this->block->getTableName() . " AS b),0)
      GROUP BY u.id");
    if ($stmt && $stmt->execute() && $result = $stmt->get_result())
      return $this->memcache->setCache(__FUNCTION__, $result->fetch_all(MYSQLI_ASSOC));
    // Catchall
    $this->debug->append("Unable to fetch all users round shares: " . $this->mysqli->error);
    return false;
  }

  /**
   * Get amount of shares for a specific user
   * @param account_id int User ID
   * @return data array invalid and valid share counts
   **/
  public function getUserShares($account_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($this->getGetCache() && $data = $this->memcache->get(__FUNCTION__ . $account_id)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT
        IFNULL(SUM(IF(our_result='Y', IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty), 0)), 0) AS valid,
        IFNULL(SUM(IF(our_result='N', IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty), 0)), 0) AS invalid
      FROM " . $this->share->getTableName() . " AS s,
           " . $this->user->getTableName() . " AS u
      WHERE
        u.username = SUBSTRING_INDEX( s.username, '.', 1 )
        AND UNIX_TIMESTAMP(s.time) >IFNULL((SELECT MAX(b.time) FROM " . $this->block->getTableName() . " AS b),0)
        AND u.id = ?");
    if ($stmt && $stmt->bind_param("i", $account_id) && $stmt->execute() && $result = $stmt->get_result())
      return $this->memcache->setCache(__FUNCTION__ . $account_id, $result->fetch_assoc());
    // Catchall
    $this->debug->append("Unable to fetch user round shares: " . $this->mysqli->error);
    return false;
  }

  /**
   * Admin panel specific query
   * @return data array invlid and valid shares for all accounts
   **/
  public function getAllUserStats($filter='%') {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($this->getGetCache() && $data = $this->memcache->get(__FUNCTION__ . $filter)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT
        a.id AS id,
        a.is_admin as is_admin,
        a.is_locked as is_locked,
        a.no_fees as no_fees,
        a.username AS username,
        a.donate_percent AS donate_percent,
        a.email AS email,
        IFNULL(SUM(IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty)), 0) AS shares
      FROM " . $this->user->getTableName() . " AS a
      LEFT JOIN " . $this->share->getTableName() . " AS s
      ON a.username = SUBSTRING_INDEX( s.username, '.', 1 )
      WHERE
      	a.username LIKE ?
      GROUP BY username
      ORDER BY username");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $filter) && $stmt->execute() && $result = $stmt->get_result()) {
      return $this->memcache->setCache(__FUNCTION__ . $filter, $result->fetch_all(MYSQLI_ASSOC));
    }
  }

  /**
   * Same as getUserShares for Hashrate
   * @param account_id integer User ID
   * @return data integer Current Hashrate in khash/s
   **/
  public function getUserHashrate($account_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__ . $account_id)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT
        (
          SELECT IFNULL(ROUND(SUM(IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty)) * 65536/600/1000), 0) AS hashrate
          FROM " . $this->share->getTableName() . " AS s,
               " . $this->user->getTableName() . " AS u
          WHERE u.username = SUBSTRING_INDEX( s.username, '.', 1 )
            AND s.time > DATE_SUB(now(), INTERVAL 10 MINUTE)
            AND u.id = ?
        ) + (
          SELECT IFNULL(ROUND(SUM(IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty)) * 65536/600/1000), 0) AS hashrate
          FROM " . $this->share->getArchiveTableName() . " AS s,
               " . $this->user->getTableName() . " AS u
          WHERE u.username = SUBSTRING_INDEX( s.username, '.', 1 )
            AND s.time > DATE_SUB(now(), INTERVAL 10 MINUTE)
            AND u.id = ?
        ) AS hashrate
      FROM DUAL");
    if ($this->checkStmt($stmt) && $stmt->bind_param("ii", $account_id, $account_id) && $stmt->execute() && $result = $stmt->get_result() )
      return $this->memcache->setCache(__FUNCTION__ . $account_id, $result->fetch_object()->hashrate);
    // Catchall
    $this->debug->append("Failed to fetch hashrate: " . $this->mysqli->error);
    return false;
  }

  /**
   * Same as getUserHashrate for Sharerate
   * @param account_id integer User ID
   * @return data integer Current Sharerate in shares/s
   **/
  public function getUserSharerate($account_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__ . $account_id)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT IFNULL(SUM(IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty)), 0)/600 AS sharerate
      FROM " . $this->share->getTableName() . " AS s,
           " . $this->user->getTableName() . " AS u
      WHERE u.username = SUBSTRING_INDEX( s.username, '.', 1 )
        AND s.time > DATE_SUB(now(), INTERVAL 10 MINUTE)
        AND u.id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param("i", $account_id) && $stmt->execute() && $result = $stmt->get_result() )
      return $this->memcache->setCache(__FUNCTION__ . $account_id, $result->fetch_object()->sharerate);
    // Catchall
    $this->debug->append("Failed to fetch sharerate: " . $this->mysqli->error);
    return false;
  }

  /**
   * Get hashrate for a specific worker
   * @param worker_id int Worker ID to fetch hashrate for
   * @return data int Current hashrate in khash/s
   **/
  public function getWorkerHashrate($worker_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__ . $worker_id)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT IFNULL(ROUND(SUM(IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty)) * 65536/600/1000), 0) AS hashrate
      FROM " . $this->share->getTableName() . " AS s,
           " . $this->user->getTableName() . " AS u
      WHERE u.username = SUBSTRING_INDEX( s.username, '.', 1 )
        AND s.time > DATE_SUB(now(), INTERVAL 10 MINUTE)
        AND u.id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param("i", $account_id) && $stmt->execute() && $result = $stmt->get_result() )
      return $this->memcache->setCache(__FUNCTION__ . $worker_id, $result->fetch_object()->hashrate);
    // Catchall
    $this->debug->append("Failed to fetch hashrate: " . $this->mysqli->error);
    return false;
  }

  /**
   * get our top contributors for either shares or hashrate
   * @param type string shares or hashes
   * @param limit int Limit result to $limit
   * @return data array Users with shares, account or hashrate, account
   **/
  public function getTopContributors($type='shares', $limit=15) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($this->getGetCache() && $data = $this->memcache->get(__FUNCTION__ . $type . $limit)) return $data;
    switch ($type) {
    case 'shares':
      $stmt = $this->mysqli->prepare("
        SELECT
          a.donate_percent AS donate_percent,
          a.is_anonymous AS is_anonymous,
          IFNULL(SUM(IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty)), 0) AS shares,
          SUBSTRING_INDEX( s.username, '.', 1 ) AS account
        FROM " . $this->share->getTableName() . " AS s
        LEFT JOIN " . $this->user->getTableName() . " AS a
        ON SUBSTRING_INDEX( s.username, '.', 1 ) = a.username
        WHERE our_result = 'Y'
        GROUP BY account
        ORDER BY shares DESC
        LIMIT ?");
      if ($this->checkStmt($stmt) && $stmt->bind_param("i", $limit) && $stmt->execute() && $result = $stmt->get_result())
        return $this->memcache->setCache(__FUNCTION__ . $type . $limit, $result->fetch_all(MYSQLI_ASSOC));
      $this->debug->append("Fetching shares failed: ");
      return false;
      break;

    case 'hashes':
      $stmt = $this->mysqli->prepare("
         SELECT
          a.donate_percent AS donate_percent,
          a.is_anonymous AS is_anonymous,
          IFNULL(ROUND(SUM(t1.difficulty)  * 65536/600/1000, 2), 0) AS hashrate,
          SUBSTRING_INDEX( t1.username, '.', 1 ) AS account
        FROM
        (
          SELECT IFNULL(IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty), 0) AS difficulty, username FROM " . $this->share->getTableName() . " WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE) AND our_result = 'Y'
          UNION ALL
          SELECT IFNULL(IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty), 0) AS difficulty, username FROM " . $this->share->getArchiveTableName() ." WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE) AND our_result = 'Y'
        ) AS t1
        LEFT JOIN " . $this->user->getTableName() . " AS a
        ON SUBSTRING_INDEX( t1.username, '.', 1 ) = a.username
        GROUP BY account
        ORDER BY hashrate DESC LIMIT ?");
      if ($this->checkStmt($stmt) && $stmt->bind_param("i", $limit) && $stmt->execute() && $result = $stmt->get_result())
        return $this->memcache->setCache(__FUNCTION__ . $type . $limit, $result->fetch_all(MYSQLI_ASSOC));
      $this->debug->append("Fetching shares failed: " . $this->mysqli->error);
      return false;
      break;
    }
  }

  /**
   * get Hourly hashrate for a user
   * @param account_id int User ID
   * @return data array NOT FINISHED YET
   **/
  public function getHourlyHashrateByAccount($account_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__ . $account_id)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT
      	IFNULL(ROUND(SUM(IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty)) * 65536/3600/1000), 0) AS hashrate,
        HOUR(s.time) AS hour
      FROM " . $this->share->getArchiveTableName() . " AS s, accounts AS a
      WHERE time < NOW() - INTERVAL 1 HOUR
        AND time > NOW() - INTERVAL 25 HOUR
        AND a.username = SUBSTRING_INDEX( s.username, '.', 1 )
        AND a.id = ?
      GROUP BY HOUR(time)
      UNION ALL
      SELECT
        IFNULL(ROUND(SUM(IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty)) * 65536/3600/1000), 0) AS hashrate,
        HOUR(s.time) AS hour
      FROM " . $this->share->getArchiveTableName() . " AS s, accounts AS a
      WHERE time < NOW() - INTERVAL 1 HOUR
        AND time > NOW() - INTERVAL 25 HOUR
        AND a.username = SUBSTRING_INDEX( s.username, '.', 1 )
        AND a.id = ?
      GROUP BY HOUR(time)");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $account_id, $account_id) && $stmt->execute() && $result = $stmt->get_result()) {
      $aData = array();
      while ($row = $result->fetch_assoc()) {
        $aData[$row['hour']] = $row['hashrate'];
      }
      return $this->memcache->setCache(__FUNCTION__ . $account_id, $aData);
    }
    // Catchall
    $this->debug->append("Failed to fetch hourly hashrate: " . $this->mysqli->error);
    return false;
  }

  /**
   * get Hourly hashrate for the pool 
   * @param none
   * @return data array NOT FINISHED YET
   **/
  public function getHourlyHashrateByPool() {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($this->getGetCache() && $data = $this->memcache->get(__FUNCTION__)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT
      	IFNULL(ROUND(SUM(IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty)) * 65536/3600/1000), 0) AS hashrate,
        HOUR(s.time) AS hour
      FROM " . $this->share->getArchiveTableName() . " AS s
      WHERE time < NOW() - INTERVAL 1 HOUR
        AND time > NOW() - INTERVAL 25 HOUR
      GROUP BY HOUR(time)
      UNION ALL
      SELECT
        IFNULL(ROUND(SUM(IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty)) * 65536/3600/1000), 0) AS hashrate,
        HOUR(s.time) AS hour
      FROM " . $this->share->getArchiveTableName() . " AS s
      WHERE time < NOW() - INTERVAL 1 HOUR
        AND time > NOW() - INTERVAL 25 HOUR
      GROUP BY HOUR(time)");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result()) {
      while ($row = $result->fetch_assoc()) {
        $aData[$row['hour']] = $row['hashrate'];
      }
      return $this->memcache->setCache(__FUNCTION__, @$aData);
    }
    // Catchall
    $this->debug->append("Failed to fetch hourly hashrate: " . $this->mysqli->error);
    return false;
  }
}

$statistics = new Statistics($debug, $mysqli, $config, $share, $user, $block, $memcache);
