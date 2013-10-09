<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

class Payout Extends Base {
  var $table = 'payouts';

  /**
   * Check if the user has an active payout request already
   * @param account_id int Account ID
   * @return boolean bool True of False
   **/
  public function isPayoutActive($account_id) {
    $stmt = $this->mysqli->prepare("SELECT id FROM $this->table WHERE completed = 0 AND account_id = ? LIMIT 1");
    if ($stmt && $stmt->bind_param('i', $account_id) && $stmt->execute( )&& $stmt->store_result() && $stmt->num_rows > 0)
      return true;
    return false;
  }

  /**
   * Get all new, unprocessed payout requests
   * @param none
   * @return data Associative array with DB Fields
   **/
  public function getUnprocessedPayouts() {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE completed = 0");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return false;
  }

  /**
   * Insert a new payout request
   * @param account_id Account ID
   * @return data mixed Inserted ID or false
   **/
  public function createPayout($account_id=NULL) {
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id) VALUES (?)");
    if ($stmt && $stmt->bind_param('i', $account_id) && $stmt->execute()) {
      return $stmt->insert_id;
    }
    $this->setErrorMessage('Unable to create new payout request');
    $this->debug->append('Failed to create new payout request in database: ' . $this->mysqli->error);
    return false;
  }

  /**
   * Mark a payout as processed
   * @param id int Payout ID
   * @return boolean bool True or False
   **/
  public function setProcessed($id) {
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET completed = 1 WHERE id = ?");
    if ($stmt && $stmt->bind_param('i', $id) && $stmt->execute())
      return true;
    return false;
  }
}

$oPayout = new Payout();
$oPayout->setDebug($debug);
$oPayout->setMysql($mysqli);
