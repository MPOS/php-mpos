<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Statistics {
  private $sError = '';
  private $table = 'statistics_shares';
  // This defines each statistic 
  public $valid, $invalid, $block, $user;

  public function __construct($debug, $mysqli, $config, $share) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->share = $share;
    $this->config = $config;
    $this->debug->append("Instantiated Share class", 2);
  }

  // get and set methods
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }

  public function updateShareStatistics($aStats, $iBlockId) {
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id, valid, invalid, block_id) VALUES (?, ?, ?, ?)");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('iiii', $aStats['id'], $aStats['valid'], $aStats['invalid'], $iBlockId);
      if ($stmt->execute()) {
        return true;
      }
    }
    return false;
  }

  public function getCurrentHashrate() {
    $stmt = $this->mysqli->prepare("SELECT ROUND(COUNT(id) * POW(2, " . $this->config['difficulty'] . ")/600/1000) AS hashrate FROM " . $this->share->getTableName() . " WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE)");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result() ) {
      return $result->fetch_object()->hashrate;
    }
  }

  private function checkStmt($bState) {
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Failed to prepare statement');
      return false;
    }
    return true;
  }
}

$statistics = new Statistics($debug, $mysqli, $config, $share);
