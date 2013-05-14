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

  public function getUserName($id) {
    return $this->getSingle($id, 'username');
  }

  public function getUserId($username) {
    return $this->getSingle($username, 'id', 'username');
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

  private function getSingle($value, $search='id', $field='id') {
    $stmt = $this->mysqli->prepare("SELECT $search FROM $this->table WHERE $field = ? LIMIT 1");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('i', $value);
      $stmt->execute();
      $stmt->bind_result($retval);
      $stmt->fetch();
      $stmt->close();
      return $retval;
    }
    return false;
  }

  public function getCoinAddress($userID) {
    return $this->getSingle($userID, 'coin_address');
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

  public function getTableName() {
    return $this->table;
  }

  public function getUserData($userID) {
    $this->debug->append("Fetching user information for user id: $userID");
    $stmt = $this->mysqli->prepare("
      SELECT
      id, username, pin, pass, admin,
      IFNULL(donate_percent, '0') as donate_percent, coin_address, ap_threshold,
      (
        SELECT COUNT(id)
        FROM shares
        WHERE $this->table.username = SUBSTRING_INDEX( `username` , '.', 1 )
        AND UNIX_TIMESTAMP(time) >IFNULL((SELECT MAX(time) FROM blocks),0)
        AND our_result = 'Y'
      ) AS valid,
      (
        SELECT COUNT(id)
        FROM shares
        WHERE $this->table.username = SUBSTRING_INDEX( `username` , '.', 1 )
        AND UNIX_TIMESTAMP(time) >IFNULL((SELECT MAX(time) FROM blocks),0)
        AND our_result = 'N'
      ) AS invalid
      FROM $this->table
      WHERE id = ? LIMIT 0,1");
    echo $this->mysqli->error;
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
