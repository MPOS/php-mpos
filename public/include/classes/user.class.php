<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class User {
  private $sError = '';
  private $userID = false;
  private $table = 'accounts';
  private $user = array();
  private $tableAccountBalance = 'accountBalance';
  private $tablePoolWorker = 'pool_worker';
  private $tableLedger = 'ledger';

  public function __construct($debug, $mysqli, $salt) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->salt = $salt;
    $this->debug->append("Instantiated User class", 2);
  }

  // get and set methods
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }

  public function checkLogin($username, $password) {
    $this->debug->append("Checking login for $username with password $password", 2);
    if ( $this->checkUserPassword($username, $password) ) {
      $this->createSession($username);
      return true;
    }
    return false;
  }

  public function checkPin($userId, $pin=false) {
    $this->debug->append("Confirming PIN for $userId and pin $pin", 2);
    $stmt = $this->mysqli->prepare("SELECT pin FROM $this->table WHERE id=? AND pin=? LIMIT 1");
    $pin_hash = hash('sha256', $pin.$this->salt);
    $stmt->bind_param('is', $userId, $pin_hash);
    $stmt->execute();
    $stmt->bind_result($row_pin);
    $stmt->fetch();
    $stmt->close();
    return $pin_hash === $row_pin;
  }

  private function getSingle($userID, $search='id', $field='id', $table='') {
    if ( empty($table) ) {
      $table = $this->table;
    }
    // Hack for inconsistent field names
    $stmt = $this->mysqli->prepare("SELECT $field FROM $table WHERE $search=? LIMIT 1");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('i', $userID);
      $stmt->execute();
      $stmt->bind_result($value);
      $stmt->fetch();
      $stmt->close();
      return $value;
    }
    return false;
  }
  private function updateSingle($userID, $field, $table) {
    $stmt = $this->mysqli->prepare("UPDATE $table SET " . $field['name'] . " = ? WHERE userId = ? LIMIT 1");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param($field['type'].'i', $field['value'], $userID);
      $stmt->execute();
      $stmt->close();
      return true;
    }
    return false;
  }

  public function addLedger($userID, $balance, $address, $fee=0.1) {
    $stmt = $this->mysqli->prepare("INSERT INTO $this->tableLedger (userId, transType, amount, sendAddress, feeAmount) VALUES (?, 'Debit_MP', ?, ?, ?)");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('idsd', $userID, $balance, $address, $fee);
      $stmt->execute();
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

  public function updatePassword($userID, $current, $new1, $new2) {
    if ($new1 !== $new2) {
      $this->setErrorMessage( 'New passwords do not match' );
      return false;
    }
    if ( strlen($new1) < 8 ) {
      $this->setErrorMessage( 'New password is too short, please use more than 8 chars' );
      return false;
    }
    $current = hash('sha256', $current.$this->salt);
    $new = hash('sha256', $new1.$this->salt);
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET pass = ? WHERE ( id = ? AND pass = ? )");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('sis', $new, $userID, $current);
      $stmt->execute();
      if ($stmt->errno == 0 && $stmt->affected_rows === 1) {
        return true;
      }
      $stmt->close();
    }
    $this->setErrorMessage( 'Unable to update password, current password wrong?' );
    return false;
  }

  public function updateAccount($userID, $address, $threshold, $donate) {
    $bUser = false;
    $threshold = min(250, max(0, floatval($threshold)));
    if ($threshold < 1) $threshold = 0.0;
    $donate = min(100, max(0, floatval($donate)));

    $stmt = $this->mysqli->prepare("UPDATE $this->table SET coin_address = ?, ap_threshold = ?, donate_percent = ? WHERE id = ?");
    $stmt->bind_param('sddi', $address, $threshold, $donate, $userID);
    $stmt->execute();
    if ( $stmt->errno == 0 ) {
      $stmt->close();
      return true;
    }
    return false;
  }
  // set/get methods
  public function getPaid($userID) {
    return $this->getSingle($userID, 'userId', 'paid', $this->tableAccountBalance);
  }
  public function getBalance($userID) {
    return $this->getSingle($userID, 'userId', 'balance', $this->tableAccountBalance);
  }
  public function getLtcAddress($userID) {
    return $this->getSingle($userID, 'id', 'coin_address', $this->table);
  }
  public function getUserName($userID) {
    return $this->getSingle($userID, 'id', 'username', $this->table);
  }

  public function setPaid($userID, $paid) {
    $field = array('name' => 'paid', 'type' => 'd', 'value' => $paid);
    return $this->updateSingle($userID, $field, $this->tableAccountBalance);
  }
  public function setBalance($userID, $balance) {
    $field = array('name' => 'balance', 'type' => 'd', 'value' => $balance);
    return $this->updateSingle($userID, $field, $this->tableAccountBalance);
  }

  private function checkUserPassword($username, $password) {
    $user = array();
    $stmt = $this->mysqli->prepare("SELECT username, id FROM $this->table WHERE username=? AND pass=? LIMIT 1");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('ss', $username, hash('sha256', $password.$this->salt));
      $stmt->execute();
      $stmt->bind_result($row_username, $row_id);
      $stmt->fetch();
      $stmt->close();
      // Store the basic login information
      $this->user = array('username' => $row_username, 'id' => $row_id);
      return $username === $row_username;
    }
    return false;
  }

  private function createSession($username) {
    $this->debug->append("Log in user to _SESSION", 2);
    session_regenerate_id(true);
    $_SESSION['AUTHENTICATED'] = '1';
    // $this->user from checkUserPassword
    $_SESSION['USERDATA'] = $this->user;
  }

  public function logoutUser() {
    session_destroy();
    session_regenerate_id(true);
    return true;
  }

  public function getUserData($userID) {
    $this->debug->append("Fetching user information for user id: $userID");
    $stmt = $this->mysqli->prepare("
      SELECT
      id, username, pin, pass, admin,
      IFNULL(donate_percent, '0') as donate_percent, coin_address, ap_threshold
      FROM $this->table
      WHERE id = ? LIMIT 0,1");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('i', $userID);
      if (!$stmt->execute()) {
        $this->debug->append('Failed to execute statement');
        return false;
      }
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_assoc();
    }
    $this->debug->append("Failed to fetch user information for $userID");
    return false;
  }

  // Get 15 most recent transactions
  public function getTransactions($userID, $start=0) {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->tableLedger where userId = ? ORDER BY timestamp DESC LIMIT ?,15");
    if ($this->checkStmt($stmt)) {
      if(!$stmt->bind_param('ii', $userID, $start)) return false;
      $stmt->execute();
      $result = $stmt->get_result();
      return $result->fetch_all(MYSQLI_ASSOC);
    }
    return false;
  }

  // Worker code, could possibly be moved to it's own class someday
  public function updateWorkers($userID, $data) {
    $username = $this->getUserName($userID);
    foreach ($data as $key => $value) {
      // Prefix the WebUser to Worker name
      $value['username'] = "$username." . $value['username'];
      $stmt = $this->mysqli->prepare("UPDATE $this->tablePoolWorker SET password = ?, username = ? WHERE associatedUserId = ? AND id = ?");
      if ($this->checkStmt($stmt)) {
        if (!$stmt->bind_param('ssii', $value['password'], $value['username'], $userID, $key)) return false;
        if (!$stmt->execute()) return false;
        $stmt->close();
      }
    }
    return true;
  }
  public function getWorkers($userID) {
    $stmt = $this->mysqli->prepare("SELECT id, username, password, active, hashrate FROM $this->tablePoolWorker WHERE associatedUserId = ? ORDER BY username ASC");
    if ($this->checkStmt($stmt)) {
      if (!$stmt->bind_param('i', $userID)) return false;
      if (!$stmt->execute()) return false;
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_all(MYSQLI_ASSOC);
    }
    return false;
  }
  public function addWorker($userID, $workerName, $workerPassword) {
    $username = $this->getUserName($userID);
    $workerName = "$username.$workerName";
    $stmt = $this->mysqli->prepare("INSERT INTO pool_worker (associatedUserId, username, password) VALUES(?, ?, ?)");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('iss', $userID, $workerName, $workerPassword);
      if (!$stmt->execute()) {
        $this->setErrorMessage( 'Failed to add worker' );
        if ($stmt->sqlstate == '23000') $this->setErrorMessage( 'Worker already exists' );
        return false;
      }
      return true;
    }
    return false;
  }
  public function deleteWorker($userID, $workerID) {
    $stmt = $this->mysqli->prepare("DELETE FROM $this->tablePoolWorker WHERE associatedUserId = ? AND id = ?");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('ii', $userID, $workerID);
      if ($stmt->execute() && $stmt->affected_rows == 1) {
        $stmt->close;
        return true;
      } else {
        $this->setErrorMessage( 'Unable to delete worker' );
      }
    }
    return false;
  }

  public function register($username, $password1, $password2, $pin, $email1='', $email2='') {
    if (strlen($password1) < 8) { 
      $this->setErrorMessage( 'Password is too short, minimum of 8 characters required' );
      return false;
    }
    if ($password1 !== $password2) {
      $this->setErrorMessage( 'Password do not match' );
      return false;
    }
    if (!empty($email1) && !filter_var($email1, FILTER_VALIDATE_EMAIL)) {
      $this->setErrorMessage( 'Invalid e-mail address' );
      return false;
    }
    if ($email1 !== $email2) {
      $this->setErrorMessage( 'E-mail do not match' );
      return false;
    }
    if (!is_numeric($pin) || strlen($pin) > 4 || strlen($pin) < 4) {
      $this->setErrorMessage( 'Invalid PIN' );
      return false;
    }
    $apikey = hash("sha256",$username.$salt);
    $stmt = $this->mysqli->prepare("
      INSERT INTO $this->table (username, pass, email, pin, api_key)
        VALUES (?, ?, ?, ?, ?)
          ");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('sssss', $username, hash("sha256", $password1.$this->salt), $email1, hash("sha256", $pin.$this->salt), $apikey);
      if (!$stmt->execute()) {
        $this->setErrorMessage( 'Unable to register' );
        if ($stmt->sqlstate == '23000') $this->setErrorMessage( 'Username already exists' );
        return false;
      }
      $stmt->close();
      return true;
    }
    return false;
  }
}

$user = new User($debug, $mysqli, SALT);
