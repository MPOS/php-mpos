<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class RoundStats extends Base {
  private $tableTrans = 'transactions';
  private $tableStats = 'statistics_shares';
  private $tableBlocks = 'blocks';
  private $tableUsers = 'accounts';

  /**
   * Get next block for round stats
   **/
  public function getNextBlock($iHeight=0) {
    $stmt = $this->mysqli->prepare("
      SELECT height
      FROM " . $this->block->getTableName() . "
      WHERE height > ?
      ORDER BY height ASC
      LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $iHeight) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->height;
    return $this->sqlError();
  }

  /**
   * Get prev block for round stats
   **/
  public function getPreviousBlock($iHeight=0) {
    $stmt = $this->mysqli->prepare("
      SELECT height
      FROM " . $this->block->getTableName() . "
      WHERE height < ?
      ORDER BY height DESC
      LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $iHeight) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->height;
    return $this->sqlError();
  }

  /**
   * search for block height
   **/
  public function searchForBlockHeight($iHeight=0) {
    $stmt = $this->mysqli->prepare("
       SELECT height 
       FROM " . $this->block->getTableName() . "
       WHERE height >= ?
       ORDER BY height ASC 
       LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $iHeight) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->height;
    return $this->sqlError();
  }

  /**
   * get next block for stats paging
   **/
  public function getNextBlockForStats($iHeight=0, $limit=10) {
    $stmt = $this->mysqli->prepare("
      SELECT MAX(x.height) AS height
      FROM (
        SELECT height FROM " . $this->block->getTableName() . "
        WHERE height >= ?
        ORDER BY height ASC LIMIT ?
      ) AS x");
    if ($this->checkStmt($stmt) && $stmt->bind_param("ii", $iHeight, $limit) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->height;
    return $this->sqlError();
  }

  /**
   * Get details for block height
   * @param height int Block Height
   * @return data array Block information from DB
   **/
  public function getDetailsForBlockHeight($iHeight=0) {
    $stmt = $this->mysqli->prepare("
      SELECT 
      b.id, height, blockhash, amount, confirmations, difficulty, FROM_UNIXTIME(time) as time, shares,
      IF(a.is_anonymous, 'anonymous', a.username) AS finder,
      ROUND((difficulty * 65535) / POW(2, (" . $this->config['difficulty'] . " -16)), 0) AS estshares,
      (time - (SELECT time FROM $this->tableBlocks WHERE height < ? ORDER BY height DESC LIMIT 1)) AS round_time
      FROM " . $this->block->getTableName() . " as b
      LEFT JOIN " . $this->user->getTableName() . " AS a ON b.account_id = a.id
      WHERE b.height = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $iHeight, $iHeight) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    return $this->sqlError();
  }

  /**
   * Get shares statistics for round block height
   * @param height int Block Height
   * @return data array Block information from DB
   **/
  public function getRoundStatsForAccounts($iHeight=0) {
    $stmt = $this->mysqli->prepare("
      SELECT
        a.id,
        a.username,
        a.is_anonymous,
        s.valid,
        s.invalid
        FROM " . $this->statistics->getTableName() . " AS s
        LEFT JOIN " . $this->block->getTableName() . " AS b ON s.block_id = b.id
        LEFT JOIN " . $this->user->getTableName() . " AS a ON a.id = s.account_id
        WHERE b.height = ?
        GROUP BY username ASC
        ORDER BY valid DESC
        ");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $iHeight) && $stmt->execute() && $result = $stmt->get_result()) {
      $aData = null;
      while ($row = $result->fetch_assoc()) {
        $aData[$row['id']] = $row;
      }
      return $aData;
    }
    return $this->sqlError();
  }

  /**
   * Get pplns statistics for round block height
   * @param height int Block Height
   * @return data array Block information from DB
   **/
  public function getPPLNSRoundStatsForAccounts($iHeight=0) {
    $stmt = $this->mysqli->prepare("
      SELECT
        a.username,
        a.is_anonymous,
        s.pplns_valid,
        s.pplns_invalid
        FROM " . $this->statistics->getTableName() . " AS s
        LEFT JOIN " . $this->block->getTableName() . " AS b ON s.block_id = b.id
        LEFT JOIN " . $this->user->getTableName() . " AS a ON a.id = s.account_id
        WHERE b.height = ?
        GROUP BY username ASC
        ORDER BY pplns_valid DESC
        ");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $iHeight) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError();
  }

  /**
   * Get total valid pplns shares for block height
   **/
  public function getPPLNSRoundShares($iHeight=0) {
    $stmt = $this->mysqli->prepare("
      SELECT
        SUM(s.pplns_valid) AS pplns_valid
        FROM " . $this->statistics->getTableName() . " AS s
        LEFT JOIN " . $this->block->getTableName() . " AS b ON s.block_id = b.id
        WHERE b.height = ?
        ");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $iHeight) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->pplns_valid;
    return $this->sqlError();
  }

  /**
   * Get all transactions for round block height for admin
   * @param height int Block Height
   * @return data array Block round transactions
   **/
  public function getAllRoundTransactions($iHeight=0) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
      t.id AS id,
      a.id AS uid,
      a.username AS username,
      a.is_anonymous,
      t.type AS type,
      t.amount AS amount
      FROM " . $this->transaction->getTableName() . " AS t
      LEFT JOIN " . $this->block->getTableName() . " AS b ON t.block_id = b.id
      LEFT JOIN " . $this->user->getTableName() . " AS a ON t.account_id = a.id
      WHERE b.height = ? AND t.type = 'Credit'
      ORDER BY amount DESC");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $iHeight) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    $this->debug->append('Unable to fetch transactions');
    return $this->sqlError();
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
      FROM " . $this->transaction->getTableName() . " AS t
      LEFT JOIN " . $this->block->getTableName() . " AS b ON t.block_id = b.id
      LEFT JOIN " . $this->user->getTableName() . " AS a ON t.account_id = a.id
      WHERE b.height = ? AND a.id = ?
      ORDER BY id ASC");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $iHeight, $id) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    $this->debug->append('Unable to fetch transactions');
    return $this->sqlError();
  }

  /**
   * Get ALL last blocks from height for admin panel
   **/
  public function getAllReportBlocksFoundHeight($iHeight=0, $limit=10) {
    $stmt = $this->mysqli->prepare("
      SELECT
        height, shares
      FROM " . $this->block->getTableName() . "
      WHERE height <= ?
      ORDER BY height DESC LIMIT ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param("ii", $iHeight, $limit) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError();
  }

  /**
   * Get USER last blocks from height for admin panel
   **/
  public function getUserReportBlocksFoundHeight($iHeight=0, $limit=10, $iUser) {
    $stmt = $this->mysqli->prepare("
      SELECT
        b.height, b.shares
        FROM " . $this->block->getTableName() . " AS b
        LEFT JOIN " . $this->statistics->getTableName() . " AS s ON s.block_id = b.id
        LEFT JOIN " . $this->user->getTableName() . " AS a ON a.id = s.account_id 
      WHERE b.height <= ? AND a.id = ?
      ORDER BY height DESC LIMIT ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('iii', $iHeight, $iUser, $limit) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError();
  }

  /**
   * Get shares for block height for user admin panel
   **/
  public function getRoundStatsForUser($iHeight=0, $iUser) {
    $stmt = $this->mysqli->prepare("
      SELECT
        s.valid,
        s.invalid,
        s.pplns_valid,
        s.pplns_invalid
        FROM " . $this->statistics->getTableName() . " AS s
        LEFT JOIN " . $this->block->getTableName() . " AS b ON s.block_id = b.id
        LEFT JOIN " . $this->user->getTableName() . " AS a ON a.id = s.account_id
        WHERE b.height = ? AND a.id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $iHeight, $iUser) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    return $this->sqlError();
  }

  /**
   * Get credit transactions for round block height for admin panel
   **/
  public function getUserRoundTransHeight($iHeight=0, $iUser) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
      IFNULL(t.amount, 0) AS amount
      FROM " . $this->transaction->getTableName() . " AS t
      LEFT JOIN " . $this->block->getTableName() . " AS b ON t.block_id = b.id
      LEFT JOIN " . $this->user->getTableName() . " AS a ON t.account_id = a.id
      WHERE b.height = ? AND t.type = 'Credit' AND t.account_id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $iHeight, $iUser) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->amount;
    $this->debug->append('Unable to fetch transactions');
    return $this->sqlError();
  }
}

$roundstats = new RoundStats();
$roundstats->setDebug($debug);
$roundstats->setMysql($mysqli);
$roundstats->setConfig($config);
$roundstats->setErrorCodes($aErrorCodes);
$roundstats->setUser($user);
$roundstats->setStatistics($statistics);
$roundstats->setBlock($block);
$roundstats->setTransaction($transaction);
