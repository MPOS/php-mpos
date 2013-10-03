<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Block {
  private $sError = '';
  private $table = 'blocks';
  // This defines each block
  public $height, $blockhash, $confirmations, $time, $accounted;

  public function __construct($debug, $mysqli, $config) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->config = $config;
    $this->debug->append("Instantiated Block class", 2);
  }

  // get and set methods
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }
  public function getTableName() {
    return $this->table;
  }

  /**
   * Specific method to fetch the latest block found
   * @param none
   * @return data array Array with database fields as keys
   **/
  public function getLast() {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table ORDER BY height DESC LIMIT 1");
    if ($this->checkStmt($stmt)) {
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_assoc();
    }
    return false;
  }

  /**
   * Get a specific block, by block height
   * @param height int Block Height
   * @return data array Block information from DB
   **/
  public function getBlock($height) {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE height = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $height) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    return false;
  }

  /**
   * Get our last, highest share ID inserted for a block
   * @param none
   * @return int data Share ID
   **/
  public function getLastShareId() {
    $stmt = $this->mysqli->prepare("SELECT MAX(share_id) AS share_id FROM $this->table LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->share_id;
    return false;
  }

  /**
   * Fetch all blocks without a share ID
   * @param order string Sort order, default ASC
   * @return data array Array with database fields as keys
   **/
  public function getAllUnsetShareId($order='ASC') {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE ISNULL(share_id) ORDER BY height $order");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return false;
  }

  /**
   * Fetch all unaccounted blocks
   * @param order string Sort order, default ASC
   * @return data array Array with database fields as keys
   **/
  public function getAllUnaccounted($order='ASC') {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE accounted = 0 ORDER BY height $order");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return false;
  }

  /**
   * Get total amount of blocks in our table
   * @param noone
   * @return data int Count of rows
   **/
  public function getBlockCount() {
    $stmt = $this->mysqli->prepare("SELECT COUNT(id) AS blocks FROM $this->table");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return (int)$result->fetch_object()->blocks;
    return false;
  }

  /**
   * Fetch our average share count for the past N blocks
   * @param limit int Maximum blocks to check
   * @return data float Float value of average shares
   **/
  public function getAvgBlockShares($height, $limit=1) {
    $stmt = $this->mysqli->prepare("SELECT AVG(x.shares) AS average FROM (SELECT shares FROM $this->table WHERE height <= ? ORDER BY height DESC LIMIT ?) AS x");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $height, $limit) && $stmt->execute() && $result = $stmt->get_result())
      return (float)$result->fetch_object()->average;
    return false;
  }

  /**
   * Fetch our average rewards for the past N blocks
   * @param limit int Maximum blocks to check
   * @return data float Float value of average shares
   **/
  public function getAvgBlockReward($limit=1) {
    $stmt = $this->mysqli->prepare("SELECT AVG(x.amount) AS average FROM (SELECT amount FROM $this->table ORDER BY height DESC LIMIT ?) AS x");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $limit) && $stmt->execute() && $result = $stmt->get_result())
      return (float)$result->fetch_object()->average;
    return false;
  }

  /**
   * Fetch all unconfirmed blocks from table
   * @param confirmations int Required confirmations to consider block confirmed
   * @return data array Array with database fields as keys
   **/
  public function getAllUnconfirmed($confirmations=120) {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE confirmations < ? AND confirmations > -1");
    if ($this->checkStmt($stmt) && $stmt->bind_param("i", $confirmations) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return false;
  }

  /**
   * Update confirmations for an existing block
   * @param block_id int Block ID to update
   * @param confirmations int New confirmations value
   * @return bool
   **/
  public function setConfirmations($block_id, $confirmations) {
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET confirmations = ? WHERE id = ?");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param("ii", $confirmations, $block_id) or die($stmt->error);
      $stmt->execute() or die("Failed");
      $stmt->close();
      return true;
    }
    return false;
  }

  /**
   * Fetch all blocks ordered by DESC height
   * @param order string ASC or DESC ordering
   * @return data array Array with database fields as keys
   **/
  public function getAll($order='DESC') {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table ORDER BY height $order");
    if ($this->checkStmt($stmt)) {
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_all(MYSQLI_ASSOC);
    }
    return false;
  }

  /**
   * Add new new block to the database
   * @param block array Block data as an array, see bind_param
   * @return bool
   **/
  public function addBlock($block) {
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (height, blockhash, confirmations, amount, difficulty, time) VALUES (?, ?, ?, ?, ?, ?)");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('isiddi', $block['height'], $block['blockhash'], $block['confirmations'], $block['amount'], $block['difficulty'], $block['time']);
      if (!$stmt->execute()) {
        $this->debug->append("Failed to execute statement: " . $stmt->error);
        $this->setErrorMessage($stmt->error);
        $stmt->close();
        return false;
      }
      $stmt->close();
      return true;
    }
    return false;
  }

  public function getLastUpstreamId() {
    $stmt = $this->mysqli->prepare("
      SELECT MAX(share_id) AS share_id FROM $this->table
      ");
    if ($this->checkStmt($stmt) && $stmt->execute() && $stmt->bind_result($share_id) && $stmt->fetch())
      return $share_id ? $share_id : 0;
    // Catchall
    return false;
  }

  /**
   * Update a single column within a single row
   * @param block_id int Block ID to update
   * @param field string Column name to update
   * @param value string Value to insert
   * @return bool
   **/
  private function updateSingle($block_id, $field, $value) {
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET $field = ? WHERE id = ?");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('ii', $value, $block_id);
      if (!$stmt->execute()) {
        $this->debug->append("Failed to update block ID $block_id with finder ID $account_id");
        $stmt->close();
        return false;
      }
      $stmt->close();
      return true;
    }
    return false;
  }

  /**
   * Set finder of a block
   * @param block_id int Block ID
   * @param account_id int Account ID of finder
   * @return bool
   **/
  public function setFinder($block_id, $account_id=NULL) {
    return $this->updateSingle($block_id, 'account_id', $account_id);
  }

  /**
   * Set finding share for a block
   * @param block_id int Block ID
   * @param share_id int Upstream valid share ID
   * @return bool
   **/
  public function setShareId($block_id, $share_id) {
    return $this->updateSingle($block_id, 'share_id', $share_id);
  }

  /**
   * Set counted shares for a block
   * @param block_id int Block ID
   * @param shares int Share count
   * @return bool
   **/
  public function setShares($block_id, $shares=NULL) {
    return $this->updateSingle($block_id, 'shares', $shares);
  }

  /**
   * Set block to be accounted for
   * @param block_id int Block ID
   * @return bool
   **/
  public function setAccounted($block_id=NULL) {
    if (empty($block_id)) return false;
    return $this->updateSingle($block_id, 'accounted', 1);
  }

  /**
   * Helper function
   **/
  private function checkStmt($bState) {
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Internal application Error');
      return false;
    }
    return true;
  }
}

// Automatically load our class for furhter usage
$block = new Block($debug, $mysqli, $config);
