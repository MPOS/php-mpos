<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Ledger {
  private $sError = '';
  private $table = 'blocks';
  // This defines each block
  public $height, $blockhash, $confirmations, $difficulty, $time;

  public function __construct($debug, $mysqli, $salt) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->debug->append("Instantiated Ledger class", 2);
  }

  // get and set methods
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
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

  private function checkStmt($bState) {
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Internal application Error');
      return false;
    }
    return true;
  }
}

$ledger = new Ledger($debug, $mysqli, SALT);
