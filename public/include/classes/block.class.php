<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class Block extends Base {
  protected $table = 'blocks';

  /**
   * Specific method to fetch the latest block found
   * @param none
   * @return data array Array with database fields as keys
   **/
  public function getLast() {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table ORDER BY height DESC LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    return $this->sqlError();
  }
  
  /**
   * Specific method to fetch the latest block found that is VALID
   * @param none
   * @return data array Array with database fields as keys
   **/
  public function getLastValid() {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE confirmations > -1 ORDER BY height DESC LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    return $this->sqlError();
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
    return $this->sqlError();
  }

  /**
   * Get a specific block, by share_id
   * @param share_id int Blocks share_id
   * @return data array Block information from DB
   **/
  public function getBlockByShareId($share_id) {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE share_id = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $share_id) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    return $this->sqlError();
  }

  /**
   * Get a specific block, by id
   * @param share_id int Blocks share_id
   * @return data array Block information from DB
   **/
  public function getBlockById($id) {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE id = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $id) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    return $this->sqlError();
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
    return $this->sqlError();
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
    return $this->sqlError(); 
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
    return $this->sqlError();
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
    return $this->sqlError();
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
    return $this->sqlError();
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
    return $this->sqlError();
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
    return $this->sqlError();
  }

  /**
   * Update confirmations for an existing block
   * @param block_id int Block ID to update
   * @param confirmations int New confirmations value
   * @return bool
   **/
  public function setConfirmations($block_id, $confirmations) {
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET confirmations = ? WHERE id = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param("ii", $confirmations, $block_id) && $stmt->execute())
      return true;
    return $this->sqlError();
  }

  /**
   * Fetch all blocks ordered by DESC height
   * @param order string ASC or DESC ordering
   * @return data array Array with database fields as keys
   **/
  public function getAll($order='DESC') {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table ORDER BY height $order");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError();
  }

  /**
   * Add new new block to the database
   * @param block array Block data as an array, see bind_param
   * @return bool
   **/
  public function addBlock($block) {
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (height, blockhash, confirmations, amount, difficulty, time) VALUES (?, ?, ?, ?, ?, ?)");
    if ($this->checkStmt($stmt) && $stmt->bind_param('isiddi', $block['height'], $block['blockhash'], $block['confirmations'], $block['amount'], $block['difficulty'], $block['time']) && $stmt->execute())
      return true;
    return $this->sqlError();
  }

  /**
   * Get our last inserted upstream ID from table
   * @param none
   * @return mixed upstream ID or 0, false on error
   **/
  public function getLastUpstreamId() {
    $stmt = $this->mysqli->prepare("SELECT MAX(share_id) AS share_id FROM $this->table");
    if ($this->checkStmt($stmt) && $stmt->execute() && $stmt->bind_result($share_id) && $stmt->fetch())
      return $share_id ? $share_id : 0;
    return $this->sqlError();
  }

  /**
   * Set finder of a block
   * @param block_id int Block ID
   * @param account_id int Account ID of finder
   * @return bool
   **/
  public function setFinder($block_id, $account_id=NULL) {
    $field = array( 'name' => 'account_id', 'value' => $account_id, 'type' => 'i' );
    return $this->updateSingle($block_id, $field);
  }
  
  /**
   * Set finding worker of a block
   * @param block_id int Block ID
   * @param worker string Worker Name of finder
   * @return bool
   **/
  public function setFindingWorker($block_id, $worker=NULL) {
    $field = array( 'name' => 'worker_name', 'value' => $worker, 'type' => 's' );
    return $this->updateSingle($block_id, $field);
  }

  /**
   * Set finding share for a block
   * @param block_id int Block ID
   * @param share_id int Upstream valid share ID
   * @return bool
   **/
  public function setShareId($block_id, $share_id) {
    $field = array( 'name' => 'share_id', 'value' => $share_id, 'type' => 'i');
    return $this->updateSingle($block_id, $field);
  }

  /**
   * Set counted shares for a block
   * @param block_id int Block ID
   * @param shares int Share count
   * @return bool
   **/
  public function setShares($block_id, $shares=NULL) {
    $field = array( 'name' => 'shares', 'value' => $shares, 'type' => 'i');
    return $this->updateSingle($block_id, $field);
  }

  /**
   * Set block to be accounted for
   * @param block_id int Block ID
   * @return bool
   **/
  public function setAccounted($block_id=NULL) {
    if (empty($block_id)) return false;
    $field = array( 'name' => 'accounted', 'value' => 1, 'type' => 'i');
    return $this->updateSingle($block_id, $field);
  }

  /**
   * Fetch the average amount of the past N blocks
   * @param limit int Block limit
   * @param return mixed Block array or false
   **/
  public function getAverageAmount($limit=10) {
    $stmt = $this->mysqli->prepare("SELECT IFNULL(AVG(amount), " . $this->config['reward'] . ") as avg_amount FROM ( SELECT amount FROM $this->table ORDER BY id DESC LIMIT ?) AS t1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $limit) && $stmt->execute() && $result = $stmt->get_result()) {
      return $result->fetch_object()->avg_amount;
    } else {
      $this->setErrorMessage('Failed to get average award from blocks');
      return $this->sqlError();
    }
  }
}

// Automatically load our class for furhter usage
$block = new Block();
$block->setDebug($debug);
$block->setMysql($mysqli);
$block->setConfig($config);
$block->setErrorCodes($aErrorCodes);
