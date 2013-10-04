<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class RoundStats {
  private $sError = '';
  private $tableTrans = 'transactions';
  private $tableStats = 'statistics_shares';
  private $tableBlocks = 'blocks';
  private $tableUsers = 'accounts';

  public function __construct($debug, $mysqli, $config) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->config = $config;
    $this->debug->append("Instantiated RoundStats class", 2);
  }

  // get and set methods
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }

  /**
   * Get next block for round stats
   **/
  public function getNextBlock($iHeight=0) {
    $stmt = $this->mysqli->prepare("
      SELECT height
      FROM $this->tableBlocks
      WHERE height > ?
      ORDER BY height ASC
      LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $iHeight) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->height;
    return false;
  }

  /**
   * Get prev block for round stats
   **/
  public function getPreviousBlock($iHeight=0) {
    $stmt = $this->mysqli->prepare("
      SELECT height
      FROM $this->tableBlocks
      WHERE height < ?
      ORDER BY height DESC
      LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $iHeight) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->height;
    return false;
  }

  /**
   * Get details for block height
   * @param height int Block Height
   * @return data array Block information from DB
   **/
  public function getDetailsForBlockHeight($iHeight=0, $isAdmin=0) {
    $stmt = $this->mysqli->prepare("
      SELECT 
      b.id, height, blockhash, amount, confirmations, difficulty, FROM_UNIXTIME(time) as time, shares,
      IF(a.is_anonymous, IF( ? , a.username, 'anonymous'), a.username) AS finder
        FROM $this->tableBlocks as b
        LEFT JOIN $this->tableUsers AS a ON b.account_id = a.id
        WHERE b.height = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $isAdmin, $iHeight) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    return false;
  }

  /**
   * Get shares statistics for round block height
   * @param height int Block Height
   * @return data array Block information from DB
   **/
  public function getRoundStatsForAccounts($iHeight=0, $isAdmin=0) {
    $stmt = $this->mysqli->prepare("
      SELECT
      IF(a.is_anonymous, IF( ? , a.username, 'anonymous'), a.username) AS username,
        s.valid,
        s.invalid
        FROM $this->tableStats AS s
        LEFT JOIN $this->tableBlocks AS b ON s.block_id = b.id
        LEFT JOIN $this->tableUsers AS a ON a.id = s.account_id
        WHERE b.height = ?
        GROUP BY username ASC
        ORDER BY valid DESC
        ");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $isAdmin, $iHeight) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return false;
  }

  /**
   * Get all transactions for round block height for admin
   * @param height int Block Height
   * @return data array Block round transactions
   **/
  public function getAllRoundTransactions($iHeight=0, $admin) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
      t.id AS id,
      IF(a.is_anonymous, IF( ? , a.username, 'anonymous'), a.username) AS username,
      t.type AS type,
      t.amount AS amount
      FROM $this->tableTrans AS t
      LEFT JOIN $this->tableBlocks AS b ON t.block_id = b.id
      LEFT JOIN $this->tableUsers AS a ON t.account_id = a.id
      WHERE b.height = ?
      ORDER BY id ASC");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $admin, $iHeight) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    $this->debug->append('Unable to fetch transactions');
    return false;
  }

  /**
   * Get transactions for round block height user id
   * @param height int Block Height
   * @param id int user id
   * @return data array Block round transactions for user id
   **/
  public function getUserRoundTransactions($iHeight=0, $id=0) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
      t.id AS id,
      a.username AS username,
      t.type AS type,
      t.amount AS amount
      FROM $this->tableTrans AS t
      LEFT JOIN $this->tableBlocks AS b ON t.block_id = b.id
      LEFT JOIN $this->tableUsers AS a ON t.account_id = a.id
      WHERE b.height = ? AND a.id = ?
      ORDER BY id ASC");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $iHeight, $id) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    $this->debug->append('Unable to fetch transactions');
    return false;
  }

  private function checkStmt($bState) {
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Internal application Error');
      return false;
    }
    return true;
  }

}

$roundstats = new RoundStats($debug, $mysqli, $config);
