<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Block {
  private $sError = '';
  private $table = 'blocks';
  // This defines each block
  public $height, $blockhash, $confirmations, $time, $accounted;

  public function __construct($debug, $mysqli, $salt) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->debug->append("Instantiated Block class", 2);
  }

  // get and set methods
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }

  public function getLast() {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table ORDER BY height DESC LIMIT 1");
    if ($this->checkStmt($stmt)) {
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_object();
    }
    return false;
  }

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

  public function setAccounted($blockhash='') {
    if ($blockhash == '') return false;
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET accounted = 1 WHERE blockhash = ?");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('s', $blockhash);
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

  private function checkStmt($bState) {
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Internal application Error');
      return false;
    }
    return true;
  }
}

$block = new Block($debug, $mysqli, SALT);
