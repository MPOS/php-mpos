<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Statistics {
  private $sError = '';
  private $table = 'statistics_shares';

  public function __construct($debug, $mysqli, $config, $share, $user) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->share = $share;
    $this->config = $config;
    $this->user = $user;
    $this->debug->append("Instantiated Share class", 2);
  }

  // get and set methods
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }

  private function checkStmt($bState) {
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Failed to prepare statement');
      return false;
    }
    return true;
  }

  public function updateShareStatistics($aStats, $iBlockId) {
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id, valid, invalid, block_id) VALUES (?, ?, ?, ?)");
    if ($this->checkStmt($stmt) && $stmt->bind_param('iiii', $aStats['id'], $aStats['valid'], $aStats['invalid'], $iBlockId) && $stmt->execute()) return true;
    // Catchall
    $this->debug->append("Failed to update share stats: " . $this->mysqli->error);
    return false;
  }

  public function getCurrentHashrate() {
    $stmt = $this->mysqli->prepare("
      SELECT SUM(hashrate) AS hashrate FROM
      (
        SELECT ROUND(COUNT(id) * POW(2, " . $this->config['difficulty'] . ")/600/1000) AS hashrate FROM " . $this->share->getTableName() . " WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE)
        UNION
        SELECT ROUND(COUNT(id) * POW(2, " . $this->config['difficulty'] . ")/600/1000) AS hashrate FROM " . $this->share->getArchiveTableName() . " WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE)
      ) AS sum");
    // Catchall
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result() ) return $result->fetch_object()->hashrate;
    $this->debug->append("Failed to get hashrate: " . $this->mysqli->error);
    return false;
  }

  public function getCurrentShareRate() {
    $stmt = $this->mysqli->prepare("
      SELECT ROUND(SUM(sharerate) / 600, 2) AS sharerate FROM
      (
        SELECT COUNT(id) AS sharerate FROM " . $this->share->getTableName() . " WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE)
        UNION ALL
        SELECT COUNT(id) AS sharerate FROM " . $this->share->getArchiveTableName() . " WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE)
      ) AS sum");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result() ) return $result->fetch_object()->sharerate;
    // Catchall
    $this->debug->append("Failed to fetch share rate: " . $this->mysqli->error);
    return false;
  }

  public function getRoundShares() {
    $stmt = $this->mysqli->prepare("
      SELECT
      ( SELECT IFNULL(count(id), 0)
      FROM " . $this->share->getTableName() . "
      WHERE UNIX_TIMESTAMP(time) >IFNULL((SELECT MAX(time) FROM blocks),0)
      AND our_result = 'Y' ) as valid,
      ( SELECT IFNULL(count(id), 0)
      FROM " . $this->share->getTableName() . "
      WHERE UNIX_TIMESTAMP(time) >IFNULL((SELECT MAX(time) FROM blocks),0)
      AND our_result = 'N' ) as invalid");
    if ( $this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result() ) return $result->fetch_assoc();
    // Catchall
    $this->debug->append("Failed to fetch round shares: " . $this->mysqli->error);
    return false;
  }

  public function getUserShares($account_id) {
    $stmt = $this->mysqli->prepare("
      SELECT
      (
        SELECT COUNT(s.id)
        FROM " . $this->share->getTableName() . " AS s, " . $this->user->getTableName() . " AS u
        WHERE u.username = SUBSTRING_INDEX( s.username, '.', 1 )
        AND UNIX_TIMESTAMP(s.time) >IFNULL((SELECT MAX(b.time) FROM blocks AS b),0)
        AND our_result = 'Y'
        AND u.id = ?
      ) AS valid,
      (
        SELECT COUNT(s.id)
        FROM " . $this->share->getTableName() . " AS s, " . $this->user->getTableName() . " AS u
        WHERE u.username = SUBSTRING_INDEX( s.username, '.', 1 )
        AND UNIX_TIMESTAMP(s.time) >IFNULL((SELECT MAX(b.time) FROM blocks AS b),0)
        AND our_result = 'N'
        AND u.id = ?
      ) AS invalid"); 
    if ($stmt && $stmt->bind_param("ii", $account_id, $account_id) && $stmt->execute() && $result = $stmt->get_result()) return $result->fetch_assoc();
    // Catchall
    $this->debug->append("Unable to fetch user round shares: " . $this->mysqli->error);
    return false;
  }

  public function getUserHashrate($account_id) {
    $stmt = $this->mysqli->prepare("
      SELECT ROUND(COUNT(s.id) * POW(2, " . $this->config['difficulty'] . ")/600/1000) AS hashrate
      FROM " . $this->share->getTableName() . " AS s,
        " . $this->user->getTableName() . " AS u
        WHERE u.username = SUBSTRING_INDEX( s.username, '.', 1 )
        AND s.time > DATE_SUB(now(), INTERVAL 10 MINUTE)
        AND u.id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param("i", $account_id) && $stmt->execute() && $result = $stmt->get_result() ) return $result->fetch_object()->hashrate;
    // Catchall
    $this->debug->append("Failed to fetch hashrate: " . $this->mysqli->error);
    return false;
  }

  public function getWorkerHashrate($worker_id) {
    $stmt = $this->mysqli->prepare("
      SELECT ROUND(COUNT(s.id) * POW(2,21)/600/1000) AS hashrate
      FROM " . $this->share->getTableName() . " AS s,
        " . $this->user->getTableName() . " AS u
        WHERE u.username = SUBSTRING_INDEX( s.username, '.', 1 )
        AND s.time > DATE_SUB(now(), INTERVAL 10 MINUTE)
        AND u.id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param("i", $account_id) && $stmt->execute() && $result = $stmt->get_result() ) return $result->fetch_object()->hashrate;
    // Catchall
    $this->debug->append("Failed to fetch hashrate: " . $this->mysqli->error);
    return false;
  }

  public function getHourlyHashrateByAccount($account_id) {
    $stmt = $this->mysqli->prepare("
      SELECT
      ROUND(COUNT(s.id) * POW(2, 12)/600/1000) AS hashrate,
        HOUR(s.time) AS hour
        FROM " . $this->share->getTableName() . " AS s, accounts AS a
        WHERE time < NOW() - INTERVAL 1 HOUR AND time > NOW() - INTERVAL 25 HOUR
        AND a.username = SUBSTRING_INDEX( s.username, '.', 1 )
        AND a.id = ?
        GROUP BY HOUR(time)
        UNION ALL
        SELECT
        ROUND(COUNT(s.id) * POW(2, 12)/600/1000) AS hashrate,
          HOUR(s.time) AS hour
          FROM " . $this->share->getArchiveTableName() . " AS s, accounts AS a
          WHERE time < NOW() - INTERVAL 1 HOUR AND time > NOW() - INTERVAL 25 HOUR
          AND a.username = SUBSTRING_INDEX( s.username, '.', 1 )
          AND a.id = ?
          GROUP BY HOUR(time)");
    if ($this->checkStmt($stmt) && $stmt->bind_param("ii", $account_id, $account_id) && $stmt->execute() && $hourlyhashrates = $stmt->get_result())
      return $hourlyhashrates->fetch_all(MYSQLI_ASSOC);
    // Catchall
    $this->debug->append("Failed to fetch hourly hashrate: " . $this->mysqli->error);
    return false;
  }
}
$statistics = new Statistics($debug, $mysqli, $config, $share, $user);
