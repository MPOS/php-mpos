<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class Share Extends Base {
  protected $table = 'shares';
  protected $tableArchive = 'shares_archive';
  private $oUpstream;
  private $iLastUpstreamId;
  // This defines each share
  public $rem_host, $username, $our_result, $upstream_result, $reason, $solution, $time, $difficulty;

  /**
   * Fetch archive tables name for this class
   * @param none
   * @return data string Table name
   **/
  public function getArchiveTableName() {
    return $this->tableArchive;
  }

  /**
   * Fetch a single share by ID
   * @param id int Share ID
   * @return array Share data
   **/
  public function getShareById($id) {
    return $this->getSingleAssoc($id);
  }

  /**
   * Update an entire shares data
   **/
  public function updateShareById($id, $data) {
    $this->debug->append("STA " . __METHOD__, 4);
    $sql = "UPDATE $this->table SET";
    $start = true;
    // Remove ID column
    unset($data['id']);
    foreach ($data as $column => $value) {
      $start == true ? $sql .= " $column = ? " : $sql .= ", $column = ?";
      $start = false;
      switch($column) {
      case 'difficulty':
        $this->addParam('d', $value);
        break;
      default:
        $this->addParam('s', $value);
        break;
      }
    }
    $sql .= " WHERE id = ? LIMIT 1";
    $this->addParam('i', $id);
    $stmt = $this->mysqli->prepare($sql);
    if ($this->checkStmt($stmt) && call_user_func_array( array($stmt, 'bind_param'), $this->getParam()) && $stmt->execute())
      return true;
    return $this->sqlError();
  }

  /**
   * Get last inserted Share ID from Database
   * Used for PPS calculations without moving to archive
   **/
  public function getLastInsertedShareId() {
    $stmt = $this->mysqli->prepare("SELECT MAX(id) AS id FROM $this->table");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->id;
    return $this->sqlError();
  }

  /**
   * Get all valid shares for this round
   * @param previous_upstream int Previous found share accepted by upstream to limit results
   * @param current_upstream int Current upstream accepted share
   * @return data int Total amount of counted shares
   **/
  public function getRoundShares($previous_upstream=0, $current_upstream) {
    $stmt = $this->mysqli->prepare("SELECT
      IFNULL(SUM(IF(s.difficulty=0, POW(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty)), 0) AS total
      FROM $this->table AS s
      LEFT JOIN " . $this->user->getTableName() . " AS a
      ON a.username = SUBSTRING_INDEX( s.username , '.', 1 )
      WHERE s.id > ? AND s.id <= ? AND s.our_result = 'Y' AND a.is_locked != 2
      ");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $previous_upstream, $current_upstream) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->total;
    return $this->sqlError();
  }

  /**
   * Fetch all shares grouped by accounts to count share per account
   * @param previous_upstream int Previous found share accepted by upstream to limit results
   * @param current_upstream int Current upstream accepted share
   * @param limit int Limit to this amount of shares for PPLNS
   * @return data array username, valid and invalid shares from account
   **/
  public function getSharesForAccounts($previous_upstream=0, $current_upstream) {
    $stmt = $this->mysqli->prepare("
      SELECT
        a.id,
        SUBSTRING_INDEX( s.username , '.', 1 ) as username,
        a.no_fees AS no_fees,
        IFNULL(SUM(IF(our_result='Y', IF(s.difficulty=0, POW(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty), 0)), 0) AS valid,
        IFNULL(SUM(IF(our_result='N', IF(s.difficulty=0, POW(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty), 0)), 0) AS invalid
      FROM $this->table AS s
      LEFT JOIN " . $this->user->getTableName() . " AS a
      ON a.username = SUBSTRING_INDEX( s.username , '.', 1 )
      WHERE s.id > ? AND s.id <= ? AND a.is_locked != 2
      GROUP BY username DESC
      ");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $previous_upstream, $current_upstream) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError();
  }

  /**
   * Fetch the highest available share ID from archive
   **/
  function getMaxArchiveShareId() {
    $stmt = $this->mysqli->prepare("SELECT MAX(share_id) AS share_id FROM $this->tableArchive");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->share_id;
    return $this->sqlError();
  }

  /**
   * We need a certain amount of valid archived shares
   * param left int Left/lowest share ID
   * param right int Right/highest share ID
   * return array data Returns an array with usernames as keys for easy access
   **/
  function getArchiveShares($iCount) {
    $iMinId = $this->getMinArchiveShareId($iCount);
    $iMaxId = $this->getMaxArchiveShareId();
    $stmt = $this->mysqli->prepare("
      SELECT
        a.id,
        SUBSTRING_INDEX( s.username , '.', 1 ) as account,
        a.no_fees AS no_fees,
        IFNULL(SUM(IF(our_result='Y', IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty), 0)), 0) AS valid,
        IFNULL(SUM(IF(our_result='N', IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty), 0)), 0) AS invalid
      FROM $this->tableArchive AS s
      LEFT JOIN " . $this->user->getTableName() . " AS a
      ON a.username = SUBSTRING_INDEX( s.username , '.', 1 )
      WHERE s.share_id > ? AND s.share_id <= ? AND a.is_locked != 2
      GROUP BY account DESC");
    if ($this->checkStmt($stmt) && $stmt->bind_param("ii", $iMinId, $iMaxId) && $stmt->execute() && $result = $stmt->get_result()) {
      $aData = NULL;
      while ($row = $result->fetch_assoc()) {
        $aData[strtolower($row['account'])] = $row;
      }
      if (is_array($aData)) return $aData;
    }
    return $this->sqlError();
  }

  /**
   * We keep shares only up to a certain point
   * This can be configured by the user.
   * @return return bool true or false
   **/
  public function purgeArchive() {
    // Fallbacks if unset
    if (!isset($this->config['archive']['purge'])) $this->config['archive']['purge'] = 5;

    $stmt = $this->mysqli->prepare("SELECT CEIL(COUNT(id) / 100 * ?) AS count FROM $this->tableArchive");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $this->config['archive']['purge']) && $stmt->execute() && $result = $stmt->get_result()) {
      $limit = $result->fetch_object()->count;
    } else {
      return $this->sqlError();
    }
    $stmt->close();
    $stmt = $this->mysqli->prepare("
      DELETE FROM $this->tableArchive WHERE time < (
        SELECT MIN(time) FROM (
          SELECT MIN(time) AS time
          FROM $this->tableArchive
          WHERE block_id = (
            SELECT MIN(id) AS minid FROM (
              SELECT id FROM " . $this->block->getTableName() . " ORDER BY height DESC LIMIT ?
            ) AS minheight
          ) UNION SELECT DATE_SUB(now(), INTERVAL ? MINUTE) AS time
        ) AS mintime
      ) LIMIT $limit
    ");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $this->config['archive']['maxrounds'], $this->config['archive']['maxage']) && $stmt->execute())
      return $stmt->affected_rows;
    return $this->sqlError();
  }

  /**
   * Move accounted shares to archive table, this step is optional
   * @param previous_upstream int Previous found share accepted by upstream to limit results
   * @param current_upstream int Current upstream accepted share
   * @param block_id int Block ID to assign shares to a specific block
   * @return bool
   **/
  public function moveArchive($current_upstream, $block_id, $previous_upstream=0) {
    if ($this->config['payout_system'] != 'pplns') {
      // We don't need archived shares that much, so only archive as much as configured
      $sql = "
        INSERT INTO $this->tableArchive (share_id, username, our_result, upstream_result, block_id, time, difficulty)
          SELECT id, username, our_result, upstream_result, ?, time, IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty) AS difficulty
          FROM $this->table
          WHERE id > ? AND id <= ?
            AND time >= DATE_SUB(now(), INTERVAL " . $this->config['archive']['maxage'] . " MINUTE)";
    } else {
      // PPLNS needs archived shares for later rounds, so we have to copy them all
      $sql = "
        INSERT INTO $this->tableArchive (share_id, username, our_result, upstream_result, block_id, time, difficulty)
          SELECT id, username, our_result, upstream_result, ?, time, IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty) AS difficulty
          FROM $this->table
          WHERE id > ? AND id <= ?";
    }
    $archive_stmt = $this->mysqli->prepare($sql);
    if ($this->checkStmt($archive_stmt) && $archive_stmt->bind_param('iii', $block_id, $previous_upstream, $current_upstream) && $archive_stmt->execute())
      return true;
    return $this->sqlError();
  }

  /**
   * Delete accounted shares from shares table
   * @param current_upstream int Current highest upstream ID
   * @param previous_upstream int Previous upstream ID
   * @return bool true or false
   **/
  public function deleteAccountedShares($current_upstream, $previous_upstream=0) {
    // Fallbacks if unset
    if (!isset($this->config['purge']['shares'])) $this->config['purge']['shares'] = 25000;
    if (!isset($this->config['purge']['sleep'])) $this->config['purge']['sleep'] = 1;

    $affected = 1;
    while ($affected > 0) {
      // Sleep first to allow any IO to cleanup
      sleep($this->config['purge']['sleep']);
      $stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE id > ? AND id <= ? ORDER BY id LIMIT " . $this->config['purge']['shares']);
      $start = microtime(true);
      if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $previous_upstream, $current_upstream) && $stmt->execute()) {
        $affected = $stmt->affected_rows;
      } else {
        return $this->sqlError();
      }
    }
    return true;
  }

  /**
   * Set/get last found share accepted by upstream: id and accounts
   **/
  public function setLastUpstreamId() {
    $this->iLastUpstreamId = @$this->oUpstream->id ? $this->oUpstream->id : 0;
  }
  public function getLastUpstreamId() {
    return @$this->iLastUpstreamId ? @$this->iLastUpstreamId : 0;
  }
  public function getUpstreamFinder() {
    return @$this->oUpstream->account;
  }
  public function getUpstreamWorker() {
    return @$this->oUpstream->worker;
  }
  public function getUpstreamShareId() {
    return @$this->oUpstream->id;
  }
  /**
   * Find upstream accepted share that should be valid for a specific block
   * Assumptions:
   *  * Shares are matching blocks in ASC order
   *  * We can skip all upstream shares of previously found ones used in a block
   * @param last int Skips all shares up to last to find new share
   * @return bool
   **/
  public function findUpstreamShare($aBlock, $last=0) {
    // Many use stratum, so we create our stratum check first
    $version = pack("I*", sprintf('%08d', $aBlock['version']));
    $previousblockhash = pack("H*", swapEndian($aBlock['previousblockhash']));
    $merkleroot = pack("H*", swapEndian($aBlock['merkleroot']) );
    $time = pack("I*", $aBlock['time']);
    $bits = pack("H*", swapEndian($aBlock['bits']));
    $nonce = pack("I*", $aBlock['nonce']);
    $header_bin = $version .  $previousblockhash . $merkleroot . $time .  $bits . $nonce;
    $header_hex = implode(unpack("H*", $header_bin));

    // Stratum supported blockhash solution entry
    $stmt = $this->mysqli->prepare("SELECT SUBSTRING_INDEX( `username` , '.', 1 ) AS account, username as worker, id FROM $this->table WHERE solution = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $aBlock['hash']) && $stmt->execute() && $result = $stmt->get_result()) {
      $this->oUpstream = $result->fetch_object();
      $this->share_type = 'stratum_blockhash';
      if (!empty($this->oUpstream->account) && !empty($this->oUpstream->worker) && is_int($this->oUpstream->id))
        return true;
    }

    // Stratum scrypt hash check
    $scrypt_hash = swapEndian(bin2hex(Scrypt::calc($header_bin, $header_bin, 1024, 1, 1, 32)));
    $stmt = $this->mysqli->prepare("SELECT SUBSTRING_INDEX( `username` , '.', 1 ) AS account, username as worker, id FROM $this->table WHERE solution = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $scrypt_hash) && $stmt->execute() && $result = $stmt->get_result()) {
      $this->oUpstream = $result->fetch_object();
      $this->share_type = 'stratum_solution';
      if (!empty($this->oUpstream->account) && !empty($this->oUpstream->worker) && is_int($this->oUpstream->id))
        return true;
    }

    // Failed to fetch via startum solution, try pushpoold
    // Fallback to pushpoold solution type
    $ppheader = sprintf('%08d', $aBlock['version']) . word_reverse($aBlock['previousblockhash']) . word_reverse($aBlock['merkleroot']) . dechex($aBlock['time']) . $aBlock['bits'] . dechex($aBlock['nonce']);
    $stmt = $this->mysqli->prepare("SELECT SUBSTRING_INDEX( `username` , '.', 1 ) AS account, username as worker, id FROM $this->table WHERE solution LIKE CONCAT(?, '%') LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $ppheader) && $stmt->execute() && $result = $stmt->get_result()) {
      $this->oUpstream = $result->fetch_object();
      $this->share_type = 'pp_solution';
      if (!empty($this->oUpstream->account) && !empty($this->oUpstream->worker) && is_int($this->oUpstream->id))
        return true;
    }

    // Still no match, try upstream result with timerange
    $stmt = $this->mysqli->prepare("
      SELECT
      SUBSTRING_INDEX( `username` , '.', 1 ) AS account, username as worker, id
      FROM $this->table
      WHERE upstream_result = 'Y'
      AND id > ?
      AND UNIX_TIMESTAMP(time) >= ?
      AND UNIX_TIMESTAMP(time) <= ( ? + 60 )
      ORDER BY id ASC LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('iii', $last, $aBlock['time'], $aBlock['time']) && $stmt->execute() && $result = $stmt->get_result()) {
      $this->oUpstream = $result->fetch_object();
      $this->share_type = 'upstream_share';
      if (!empty($this->oUpstream->account) && !empty($this->oUpstream->worker) && is_int($this->oUpstream->id))
        return true;
    }

    // We failed again, now we take ANY result matching the timestamp
    $stmt = $this->mysqli->prepare("
      SELECT
      SUBSTRING_INDEX( `username` , '.', 1 ) AS account, username as worker, id
      FROM $this->table
      WHERE our_result = 'Y'
      AND id > ?
      AND UNIX_TIMESTAMP(time) >= ?
      ORDER BY id ASC LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $last, $aBlock['time']) && $stmt->execute() && $result = $stmt->get_result()) {
      $this->oUpstream = $result->fetch_object();
      $this->share_type = 'any_share';
      if (!empty($this->oUpstream->account) && !empty($this->oUpstream->worker) && is_int($this->oUpstream->id))
        return true;
    }
    $this->setErrorMessage($this->getErrorMsg('E0052', $aBlock['height']));
    return false;
  }

  /**
   * Fetch the lowest needed share ID from shares
   **/
  function getMinimumShareId($iCount, $current_upstream) {
    $stmt = $this->mysqli->prepare("
      SELECT MIN(b.id) AS id FROM
      (
        SELECT id, @total := @total + IF(difficulty=0, POW(2, (" . $this->config['difficulty'] . " - 16)), difficulty) AS total
        FROM $this->table, (SELECT @total := 0) AS a
        WHERE our_result = 'Y'
        AND id <= ? AND @total < ?
        ORDER BY id DESC
      ) AS b
      WHERE total <= ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('iii', $current_upstream, $iCount, $iCount) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->id;
    return $this->sqlError();
  }

  /**
   * Fetch the lowest needed share ID from archive
   **/
  function getMinArchiveShareId($iCount) {
    $stmt = $this->mysqli->prepare("
      SELECT MIN(b.share_id) AS share_id FROM
      (
        SELECT share_id, @total := @total + IF(difficulty=0, POW(2, (" . $this->config['difficulty'] . " - 16)), difficulty) AS total
        FROM $this->tableArchive, (SELECT @total := 0) AS a
        WHERE our_result = 'Y'
        AND @total < ?
        ORDER BY share_id DESC
      ) AS b
      WHERE total <= ?
      ");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $iCount, $iCount) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->share_id;
    return $this->sqlError();
  }
}

$share = new Share();
$share->setDebug($debug);
$share->setMysql($mysqli);
$share->setConfig($config);
$share->setUser($user);
$share->setBlock($block);
$share->setErrorCodes($aErrorCodes);
