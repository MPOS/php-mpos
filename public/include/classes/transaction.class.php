<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Transaction {
  private $sError = '';
  private $table = 'transactions';
  private $tableBlocks = 'blocks';

  public function __construct($debug, $mysqli, $config) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->config = $config;
    $this->debug->append("Instantiated Ledger class", 2);
  }

  // get and set methods
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }

  public function addCredit($account_id, $amount, $block_id) {
    $strType = 'Credit';
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id, amount, block_id, type) VALUES (?, ?, ?, ?)");
    echo $this->mysqli->error;
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param("idis", $account_id, $amount, $block_id, $strType);
      if ($stmt->execute()) {
        $stmt->close();
        return true;
      }
    }
    return false;
  }

  public function confirmCredits() {
    $stmt = $this->mysqli->prepare("UPDATE
                              ledger AS l
                            INNER JOIN blocks as b ON l.assocBlock = b.height
                            SET l.confirmed = 1
                            WHERE b.confirmations > 120
                            AND l.confirmed = 0");
    if ($this->checkStmt($stmt)) {
      if (!$stmt->execute()) {
        $this->debug->append("Failed to execute statement: " . $stmt->error);
        $stmt->close();
        return false;
      }
      $stmt->close();
      return true;
    }
    return false;
  }

  public function getTransactions($account_id, $start=0) {
    $stmt = $this->mysqli->prepare("
      SELECT
        t.id AS id,
        t.type AS type,
        t.amount AS amount,
        t.sendAddress AS sendAddress,
        t.timestamp AS timestamp,
        b.height AS height,
        b.confirmations AS confirmations
      FROM transactions AS t
      LEFT JOIN blocks AS b ON t.block_id = b.id
      WHERE t.account_id = ?
      ORDER BY timestamp DESC
      LIMIT ? , 30");
    if ($this->checkStmt($stmt)) {
      if(!$stmt->bind_param('ii', $account_id, $start)) return false;
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
      SELECT IFNULL(c.credit - d.debit, 0) AS balance
      FROM (
        SELECT account_id, sum(t.amount) AS credit
        FROM $this->table AS t
        LEFT JOIN $this->tableBlocks AS b ON t.block_id = b.id
        WHERE type = 'Credit'
        AND b.confirmations > ?
        AND account_id = ? ) AS c
      LEFT JOIN (
        SELECT account_id, sum(amount) AS debit
        FROM $this->table
        WHERE type IN ('Debit_MP','Debit_AP')
        AND account_id = ? ) AS d
      ON c.account_id = d.account_id
      ");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param("iii", $this->config['confirmations'], $account_id, $account_id);
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

$transaction = new Transaction($debug, $mysqli, $config);
