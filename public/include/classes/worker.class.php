<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Worker {
  private $sError = '';
  private $table = 'workers';

  public function __construct($debug, $mysqli, $user, $share) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->user = $user;
    $this->share = $share;
    $this->debug->append("Instantiated Worker class", 2);
  }

  // get and set methods
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }

  private function checkStmt($bState) {
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Internal application Error');
      return false;
    }
    return true;
  }
  // Worker code, could possibly be moved to it's own class someday
  public function updateWorkers($account_id, $data) {
    $username = $this->user->getUserName($account_id);
    foreach ($data as $key => $value) {
      // Prefix the WebUser to Worker name
      $value['username'] = "$username." . $value['username'];
      $stmt = $this->mysqli->prepare("UPDATE $this->table SET password = ?, username = ? WHERE account_id = ? AND id = ?");
      if ($this->checkStmt($stmt)) {
        if (!$stmt->bind_param('ssii', $value['password'], $value['username'], $account_id, $key)) return false;
        if (!$stmt->execute()) return false;
        $stmt->close();
      }
    }
    return true;
  }
  public function getWorkers($account_id) {
    $stmt = $this->mysqli->prepare("
      SELECT $this->table.username, $this->table.password,
      ( SELECT SIGN(count(id)) FROM " . $this->share->getTableName() . " WHERE username = $this->table.username AND time > DATE_SUB(now(), INTERVAL 10 MINUTE)) AS active,
      ( SELECT ROUND(COUNT(id) * POW(2,21)/600/1000) FROM " . $this->share->getTableName() . " WHERE username = $this->table.username AND time > DATE_SUB(now(), INTERVAL 10 MINUTE)) AS hashrate
      FROM $this->table
      WHERE account_id = ?");
    if ($this->checkStmt($stmt)) {
      if (!$stmt->bind_param('i', $account_id)) return false;
      if (!$stmt->execute()) return false;
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_all(MYSQLI_ASSOC);
    }
    return false;
  }

  public function getCountAllActiveWorkers() {
    $stmt = $this->mysqli->prepare("SELECT COUNT(DISTINCT username) AS total FROM "  . $this->share->getTableName() . " WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE)");
    if ($this->checkStmt($stmt)) {
      if (!$stmt->execute()) {
        return false;
      }
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_object()->total;
    }
    return false;
  }

  public function addWorker($account_id, $workerName, $workerPassword) {
    $username = $this->user->getUserName($account_id);
    $workerName = "$username.$workerName";
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id, username, password) VALUES(?, ?, ?)");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('iss', $account_id, $workerName, $workerPassword);
      if (!$stmt->execute()) {
        $this->setErrorMessage( 'Failed to add worker' );
        if ($stmt->sqlstate == '23000') $this->setErrorMessage( 'Worker already exists' );
        return false;
      }
      return true;
    }
    return false;
  }
  public function deleteWorker($account_id, $id) {
    $stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE account_id = ? AND id = ?");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('ii', $account_id, $id);
      if ($stmt->execute() && $stmt->affected_rows == 1) {
        $stmt->close;
        return true;
      } else {
        $this->setErrorMessage( 'Unable to delete worker' );
      }
    }
    return false;
  }
}

$worker = new Worker($debug, $mysqli, $user, $share);
