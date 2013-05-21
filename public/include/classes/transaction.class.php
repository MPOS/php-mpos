<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Transaction {
  private $sError = '';
  private $table = 'transactions';
  private $tableBlocks = 'blocks';

  public function __construct($debug, $mysqli, $config, $block) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->config = $config;
    $this->block = $block;
    $this->debug->append("Instantiated Transaction class", 2);
  }

  // get and set methods
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }

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

  public function addDebit($account_id, $amount, $type='AP') {
  }

  public function getTransactions($account_id, $start=0) {
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
      LEFT JOIN blocks AS b ON t.block_id = b.id
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

  private function checkStmt($bState) {
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Internal application Error');
      return false;
    }
    return true;
  }

  public function getBalance($account_id) {
    $stmt = $this->mysqli->prepare("
      SELECT ROUND(IFNULL(t1.credit, 0) - IFNULL(t2.debit, 0) - IFNULL(t3.other, 0), 8) AS balance
      FROM
      (
        SELECT sum(t.amount) AS credit
        FROM $this->table AS t
        LEFT JOIN " . $this->block->getTableName() . " AS b ON t.block_id = b.id
        WHERE t.type = 'Credit'
        AND b.confirmations >= ?
        AND t.account_id = ?
      ) AS t1,
      (
        SELECT sum(t.amount) AS debit
        FROM $this->table AS t
        WHERE t.type IN ('Debit_MP', 'Debit_AP')
        AND t.account_id = ?
      ) AS t2,
      (
        SELECT sum(t.amount) AS other
        FROM $this->table AS t
        LEFT JOIN " . $this->block->getTableName() . " AS b ON t.block_id = b.id
        WHERE t.type IN ('Donation','Fee')
        AND b.confirmations >= ?
        AND t.account_id = ?
      ) AS t3
      ");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param("iiiii", $this->config['confirmations'], $account_id, $account_id, $this->config['confirmations'], $account_id);
      if (!$stmt->execute()) {
        $this->debug->append("Unable to execute statement: " . $stmt->error);
        $this->setErrorMessage("Fetching balance failed");
      }
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_object()->balance;
    }
    return false;
  }
}

$transaction = new Transaction($debug, $mysqli, $config, $block);
