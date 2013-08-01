<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Transaction {
  private $sError = '';
  private $table = 'transactions';
  private $tableBlocks = 'blocks';
  public $num_rows = 0;

  public function __construct($debug, $mysqli, $config, $block, $user) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->config = $config;
    $this->block = $block;
    $this->user = $user;
    $this->debug->append("Instantiated Transaction class", 2);
  }

  // get and set methods
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }

  /**
   * Add a new transaction to our class table
   * @param account_id int Account ID to book transaction for
   * @param amount float Coin amount
   * @param type string Transaction type [Credit, Debit_AP, Debit_MP, Fee, Donation, Orphan_Credit, Orphan_Fee, Orphan_Donation]
   * @param block_id int Block ID to link transaction to [optional]
   * @param coin_address string Coin address for this transaction [optional]
   * @return bool
   **/
  public function addTransaction($account_id, $amount, $type='Credit', $block_id=NULL, $coin_address=NULL) {
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id, amount, block_id, type, coin_address) VALUES (?, ?, ?, ?, ?)");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param("idiss", $account_id, $amount, $block_id, $type, $coin_address);
      if ($stmt->execute()) {
        $this->setErrorMessage("Failed to store transaction");
        $stmt->close();
        return true;
      }
    }
    return false;
  }

  /**
   * Get all transactions from start for account_id
   * @param account_id int Account ID
   * @param start int Starting point, id of transaction
   * @return data array Database fields as defined in SELECT
   **/
  public function getTransactions($account_id, $start=0) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
        t.id AS id,
        t.type AS type,
        t.amount AS amount,
        t.coin_address AS coin_address,
        t.timestamp AS timestamp,
        b.height AS height,
        b.confirmations AS confirmations
      FROM transactions AS t
      LEFT JOIN " . $this->block->getTableName() . " AS b ON t.block_id = b.id
      WHERE t.account_id = ?
      ORDER BY id DESC");
    if ($this->checkStmt($stmt)) {
      if(!$stmt->bind_param('i', $account_id)) return false;
      $stmt->execute();
      $result = $stmt->get_result();
      return $result->fetch_all(MYSQLI_ASSOC);
    }
    $this->debug->append('Unable to fetch transactions');
    return false;
  }

  /**
   * Fetch all transactions for all users
   * Optionally apply a filter
   * @param none
   * @return mixed array or false
   **/
  public function getAllTransactions($start=0,$filter=NULL,$limit=30) {
    $this->debug->append("STA " . __METHOD__, 4);
    $sql = "
      SELECT
        SQL_CALC_FOUND_ROWS
        t.id AS id,
        a.username as username,
        t.type AS type,
        t.amount AS amount,
        t.coin_address AS coin_address,
        t.timestamp AS timestamp,
        b.height AS height,
        b.blockhash AS blockhash,
        b.confirmations AS confirmations
      FROM $this->table AS t
      LEFT JOIN " . $this->block->getTableName() . " AS b ON t.block_id = b.id
      LEFT JOIN " . $this->user->getTableName() . " AS a ON t.account_id = a.id";
    if (is_array($filter)) {
      $aFilter = array();
      foreach ($filter as $key => $value) {
        if (!empty($value)) {
          switch ($key) {
          case 'type':
            $aFilter[] = "t.type = '$value'";
            break;
          case 'status':
            switch ($value) {
            case 'Confirmed':
              if (empty($filter['type']) || ($filter['type'] != 'Debit_AP' && $filter['type'] != 'Debit_MP' && $filter['type'] != 'TXFee' && $filter['type'] != 'Credit_PPS' && $filter['type'] != 'Fee_PPS' && $filter['type'] != 'Donation_PPS')) {
                $aFilter[] = "b.confirmations >= " . $this->config['confirmations'];
              }
                break;
            case 'Unconfirmed':
              $aFilter[] = "b.confirmations < " . $this->config['confirmations'] . " AND b.confirmations >= 0";
                break;
            case 'Orphan':
              $aFilter[] = "b.confirmations = -1";
                break;
            }
            break;
            case 'account':
              $aFilter[] = "LOWER(a.username) = LOWER('$value')";
              break;
            case 'address':
              $aFilter[] = "t.coin_address = '$value'";
              break;
          }
        }
      }
      if (!empty($aFilter)) {
        $sql .= " WHERE " . implode(' AND ', $aFilter);
      }
    }
    $sql .= "
      ORDER BY id DESC
      LIMIT ?,?
      ";
    $stmt = $this->mysqli->prepare($sql);
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $start, $limit) && $stmt->execute() && $result = $stmt->get_result()) {
      // Fetch matching row count
      $num_rows = $this->mysqli->prepare("SELECT FOUND_ROWS() AS num_rows");
      if ($num_rows->execute() && $row_count = $num_rows->get_result()->fetch_object()->num_rows)
        $this->num_rows = $row_count;
      return $result->fetch_all(MYSQLI_ASSOC);
    }
    $this->debug->append('Unable to fetch transactions');
    return false;
  }

  /**
   * Count the amount of transactions in the table
   **/
  public function getCountAllTransactions($filter=NULL) {
    $stmt = $this->mysqli->prepare("SELECT COUNT(id) AS total FROM $this->table");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->total;
    $this->debug->append('Failed to fetch transaction count: ' . $this->mysqli->error);
    return false;
  }
  public function getTypes() {
    $stmt = $this->mysqli->prepare("SELECT DISTINCT type FROM $this->table");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result()) {
      $aData = array('' => '');
      while ($row = $result->fetch_assoc()) {
        $aData[$row['type']] = $row['type'];
      }
      return $aData;
    }
    $this->debug->append('Failed to fetch transaction types: ' . $this->mysqli->error);
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

  /**
   * Get all donation transactions
   * Used on donors page
   * return data array Donors and amounts
   **/
  public function getDonations() {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
        SUM(t.amount) AS donation,
        a.username AS username,
        a.is_anonymous AS is_anonymous,
        a.donate_percent AS donate_percent
      FROM $this->table AS t
      LEFT JOIN " . $this->user->getTableName() . " AS a
      ON t.account_id = a.id
      LEFT JOIN blocks AS b
      ON t.block_id = b.id
      WHERE
      (
        ( t.type = 'Donation' AND b.confirmations >= " . $this->config['confirmations'] . " ) OR
        t.type = 'Donation_PPS'
      )
      GROUP BY a.username
      ");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    $this->debug->append("Failed to fetch website donors: " . $this->mysqli->error);
    return false;
  }

  /**
   * Get total balance for all users locked in wallet
   * This includes any outstanding unconfirmed transactions!
   * @param none
   * @return data double Amount locked for users
   **/
  public function getLockedBalance() {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
        ROUND((
          SUM( IF( ( t.type IN ('Credit','Bonus') OR t.type = 'Credit_PPS') AND b.confirmations >= ?, t.amount, 0 ) ) -
          SUM( IF( t.type IN ('Debit_MP', 'Debit_AP'), t.amount, 0 ) ) -
          SUM( IF( ( t.type IN ('Donation','Fee') AND b.confirmations >= ? ) OR ( t.type IN ('Donation_PPS', 'Fee_PPS', 'TXFee') ), t.amount, 0 ) )
        ), 8) AS balance
      FROM $this->table AS t
      LEFT JOIN blocks AS b
      ON t.block_id = b.id");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $this->config['confirmations'], $this->config['confirmations']) && $stmt->execute() && $stmt->bind_result($dBalance) && $stmt->fetch())
      return $dBalance;
    // Catchall
    $this->setErrorMessage('Unable to find locked credits for all users');
    $this->debug->append('MySQL query failed : ' . $this->mysqli->error);
    return false;
  }

  /**
   * Get an accounts total balance
   * @param account_id int Account ID
   * @return data float Credit - Debit - Fees - Donation
   **/
  public function getBalance($account_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
        ROUND((
          SUM( IF( ( t.type IN ('Credit','Bonus') OR t.type = 'Credit_PPS') AND b.confirmations >= ?, t.amount, 0 ) ) -
          SUM( IF( t.type IN ('Debit_MP', 'Debit_AP'), t.amount, 0 ) ) -
          SUM( IF( ( t.type IN ('Donation','Fee') AND b.confirmations >= ? ) OR ( t.type IN ('Donation_PPS', 'Fee_PPS', 'TXFee') ), t.amount, 0 ) )
        ), 8) AS confirmed,
        ROUND((
          SUM( IF( t.type IN ('Credit','Bonus') AND b.confirmations < ? AND b.confirmations >= 0, t.amount, 0 ) ) -
          SUM( IF( t.type IN ('Donation','Fee') AND b.confirmations < ? AND b.confirmations >= 0, t.amount, 0 ) )
        ), 8) AS unconfirmed,
        ROUND((
          SUM( IF( t.type IN ('Credit','Bonus') AND b.confirmations = -1, t.amount, 0) ) -
          SUM( IF( t.type IN ('Donation','Fee') AND b.confirmations = -1, t.amount, 0) )
        ), 8) AS orphaned
      FROM transactions AS t
      LEFT JOIN blocks AS b
      ON t.block_id = b.id
      AND t.account_id = ?
      ");
    if ($this->checkStmt($stmt) && $stmt->bind_param("iiiii", $this->config['confirmations'], $this->config['confirmations'], $this->config['confirmations'], $this->config['confirmations'], $account_id) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    $this->debug->append('Failed to fetch users balance: ' . $this->mysqli->error);
    return false;
  }
}

$transaction = new Transaction($debug, $mysqli, $config, $block, $user);
