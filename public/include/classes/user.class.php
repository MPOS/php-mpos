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

  public function __construct($debug, $mysqli, $salt, $config) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->salt = $salt;
    $this->config = $config;
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
    return $this->getSingle($id, 'username', 'id');
  }
  public function getUserId($username) {
    return $this->getSingle($username, 'id', 'username', 's');
  }
  public function getUserEmail($username) {
    return $this->getSingle($username, 'email', 'username', 's');
  }
  public function getUserAdmin($id) {
    return $this->getSingle($id, 'admin', 'id');
  }
  public function getUserToken($id) {
    return $this->getSingle($id, 'token', 'id');
  }
  public function getIdFromToken($token) {
    return $this->getSingle($token, 'id', 'token', 's');
  }
  public function isAdmin($id) {
    if ($this->getUserAdmin($id) == 1) return true;
    return false;
  }

  public function setUserToken($id) {
    $field = array(
      'name' => 'token',
      'type' => 's',
      'value' => hash('sha256', $id.time().$this->salt)
    );
    return $this->updateSingle($id, $field);
  }

  /**
   * Fetch all users for administrative tasks
   * @param none
   * @return data array All users with db columns as array fields
   **/
  public function getUsers($filter='%') {
    $stmt = $this->mysqli->prepare("SELECT * FROM " . $this->getTableName() . " WHERE username LIKE ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $filter) && $stmt->execute() && $result = $stmt->get_result()) {
      return $result->fetch_all(MYSQLI_ASSOC);
    }
  }

  /**
   * Check user login
   * @param username string Username
   * @param password string Password
   * @return bool
   **/
  public function checkLogin($username, $password) {
    $this->debug->append("STA " . __METHOD__, 4);
    $this->debug->append("Checking login for $username with password $password", 2);
    if ( $this->checkUserPassword($username, $password) ) {
      $this->createSession($username);
      return true;
    }
    return false;
  }

  /**
   * Check the users PIN for confirmation
   * @param userID int User ID
   * @param pin int PIN to check
   * @return bool
   **/
  public function checkPin($userId, $pin=false) {
    $this->debug->append("STA " . __METHOD__, 4);
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

  /**
   * Get a single row from the table
   * @param value string Value to search for
   * @param search Return column to search for
   * @param field string Search column
   * @param type string Type of value
   * @return array Return result
   **/
  private function getSingle($value, $search='id', $field='id', $type="i") {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT $search FROM $this->table WHERE $field = ? LIMIT 1");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param($type, $value);
      $stmt->execute();
      $stmt->bind_result($retval);
      $stmt->fetch();
      $stmt->close();
      return $retval;
    }
    return false;
  }

  /**
   * Get all users that have auto payout setup
   * @param none
   * @return data array All users with payout setup
   **/
  public function getAllAutoPayout() {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
        id, username, coin_address, ap_threshold
      FROM " . $this->getTableName() . "
      WHERE ap_threshold > 0
      AND coin_address IS NOT NULL
      ");
    if ( $this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result()) {
      return $result->fetch_all(MYSQLI_ASSOC);
    }
    $this->debug->append("Unable to fetch users with AP set");
    echo $this->mysqli->error;
    return false;
  }

  /**
   * Fetch users coin address
   * @param userID int UserID
   * @return data string Coin Address
   **/
  public function getCoinAddress($userID) {
    $this->debug->append("STA " . __METHOD__, 4);
    return $this->getSingle($userID, 'coin_address', 'id');
  }

  /**
   * Fetch users donation value 
   * @param userID int UserID
   * @return data string Coin Address
   **/
  public function getDonatePercent($userID) {
    $this->debug->append("STA " . __METHOD__, 4);
    $dPercent = $this->getSingle($userID, 'donate_percent', 'id');
    if ($dPercent > 100) $dPercent = 100;
    if ($dPercent < 0) $dPercent = 0;
    return $dPercent;
  }

  /**
   * Update a single row in a table
   * @param userID int Account ID
   * @param field string Field to update
   * @return bool
   **/
  private function updateSingle($id, $field) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET " . $field['name'] . " = ? WHERE id = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param($field['type'].'i', $field['value'], $id) && $stmt->execute())
      return true;
    $this->debug->append("Unable to update " . $field['name'] . " with " . $field['value'] . " for ID $id");
    return false;
  }

  private function checkStmt($bState) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Internal application Error');
      return false;
    }
    return true;
  }

  /**
   * Update the accounts password
   * @param userID int User ID
   * @param current string Current password
   * @param new1 string New password
   * @param new2 string New password confirmation
   * @return bool
   **/
  public function updatePassword($userID, $current, $new1, $new2) {
    $this->debug->append("STA " . __METHOD__, 4);
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

  /**
   * Update account information from the edit account page
   * @param userID int User ID
   * @param address string new coin address
   * @param threshold float auto payout threshold
   * @param donat float donation % of income
   * @return bool
   **/
  public function updateAccount($userID, $address, $threshold, $donate) {
    $this->debug->append("STA " . __METHOD__, 4);
    $bUser = false;

    // number validation checks
    if ($threshold < $this->config['ap_threshold']['min'] && $threshold != 0) {
      $this->setErrorMessage('Threshold below configured minimum of ' . $this->config['ap_threshold']['min']);
      return false;
    } else if ($threshold > $this->config['ap_threshold']['max']) {
      $this->setErrorMessage('Threshold above configured maximum of ' . $this->config['ap_threshold']['max']);
      return false;
    }
    if ($donate < 0) {
      $this->setErrorMessage('Donation below allowed 0% limit');
      return false;
    } else if ($donate > 100) {
      $this->setErrorMessage('Donation above allowed 100% limit');
      return false;
    }
    // Number sanitizer, just in case we fall through above
    $threshold = min($this->config['ap_threshold']['max'], max(0, floatval($threshold)));
    $donate = min(100, max(0, floatval($donate)));

    // We passed all validation checks so update the account
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET coin_address = ?, ap_threshold = ?, donate_percent = ? WHERE id = ?");
    $stmt->bind_param('sddi', $address, $threshold, $donate, $userID);
    $stmt->execute();
    if ( $stmt->errno == 0 ) {
      $stmt->close();
      return true;
    }
    return false;
  }

  /**
   * Check API key for authentication
   * @param key string API key hash
   * @return bool
   **/
  public function checkApiKey($key) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT api_key, id FROM $this->table WHERE api_key = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param("s", $key) && $stmt->execute() && $stmt->bind_result($api_key, $id) && $stmt->fetch()) {
      if ($api_key === $key)
        return $id;
    }
    header("HTTP/1.1 401 Unauthorized");
    die('Access denied');
  }

  /**
   * Check a password for a user
   * @param username string Username
   * @param password string Password
   * @return bool
   **/
  private function checkUserPassword($username, $password) {
    $this->debug->append("STA " . __METHOD__, 4);
    $user = array();
    $stmt = $this->mysqli->prepare("SELECT username, id, admin FROM $this->table WHERE username=? AND pass=? LIMIT 1");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('ss', $username, hash('sha256', $password.$this->salt));
      $stmt->execute();
      $stmt->bind_result($row_username, $row_id, $row_admin);
      $stmt->fetch();
      $stmt->close();
      // Store the basic login information
      $this->user = array('username' => $row_username, 'id' => $row_id, 'admin' => $row_admin);
      return $username === $row_username;
    }
    return false;
  }

  /**
   * Create a PHP session for a user
   * @param username string Username to create session for
   * @return none
   **/
  private function createSession($username) {
    $this->debug->append("STA " . __METHOD__, 4);
    $this->debug->append("Log in user to _SESSION", 2);
    session_regenerate_id(true);
    $_SESSION['AUTHENTICATED'] = '1';
    // $this->user from checkUserPassword
    $_SESSION['USERDATA'] = $this->user;
  }

  /**
   * Log out current user, destroy the session
   * @param none
   * @return true
   **/
  public function logoutUser() {
    $this->debug->append("STA " . __METHOD__, 4);
    session_destroy();
    session_regenerate_id(true);
    return true;
  }

  /**
   * Fetch this classes table name
   * @return table string This classes table name
   **/
  public function getTableName() {
    $this->debug->append("STA " . __METHOD__, 4);
    return $this->table;
  }

  /**
   * Fetch some basic user information to store for later user
   * @param userID int User ID
   * return data array Database fields as used in SELECT
   **/
  public function getUserData($userID) {
    $this->debug->append("STA " . __METHOD__, 4);
    $this->debug->append("Fetching user information for user id: $userID");
    $stmt = $this->mysqli->prepare("
      SELECT
      id, username, pin, api_key, admin,
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

  /**
   * Register a new user in the system
   * @param username string Username
   * @param password1 string Password
   * @param password2 string Password verification
   * @param pin int 4 digit PIN code
   * @param email1 string Email address
   * @param email2 string Email confirmation
   * @return bool
   **/
  public function register($username, $password1, $password2, $pin, $email1='', $email2='') {
    $this->debug->append("STA " . __METHOD__, 4);
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
    if ($this->mysqli->query("SELECT id FROM $this->table LIMIT 1")->num_rows > 0) {
      $stmt = $this->mysqli->prepare("
        INSERT INTO $this->table (username, pass, email, pin, api_key)
        VALUES (?, ?, ?, ?, ?)
        ");
    } else {
      $stmt = $this->mysqli->prepare("
        INSERT INTO $this->table (username, pass, email, pin, api_key, admin)
        VALUES (?, ?, ?, ?, ?, 1)
        ");
    }
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('sssss', $username, hash("sha256", $password1.$this->salt), $email1, hash("sha256", $pin.$this->salt), $apikey);
      if (!$stmt->execute()) {
        $this->setErrorMessage( 'Unable to register' );
        if ($stmt->sqlstate == '23000') $this->setErrorMessage( 'Username already exists' );
        echo $this->mysqli->error;
        return false;
      }
      $stmt->close();
      return true;
    }
    return false;
  }

  /**
   * User a one time token to reset a password
   * @param token string one time token
   * @param new1 string New password
   * @param new2 string New password verification
   * @return bool
   **/
  public function useToken($token, $new1, $new2) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($id = $this->getIdFromToken($token)) {
      if ($new1 !== $new2) {
        $this->setErrorMessage( 'New passwords do not match' );
        return false;
      }
      if ( strlen($new1) < 8 ) { 
        $this->setErrorMessage( 'New password is too short, please use more than 8 chars' );
        return false;
      }
      $new = hash('sha256', $new1.$this->salt);
      $stmt = $this->mysqli->prepare("UPDATE $this->table SET pass = ?, token = NULL WHERE id = ? AND token = ?");
      if ($this->checkStmt($stmt) && $stmt->bind_param('sis', $new, $id, $token) && $stmt->execute() && $stmt->affected_rows === 1) {
        return true;
      }
    } else {
      $this->setErrorMessage("Unable find user for your token");
      return false;
    }
    return false;
  }

  /**
   * Reset a password by sending a password reset mail
   * @param username string Username to reset password for
   * @param smarty object Smarty object for mail templating
   * @return bool
   **/
  public function resetPassword($username, $smarty) {
    $this->debug->append("STA " . __METHOD__, 4);
    // Fetch the users mail address
    if (!$email = $this->getUserEmail($username)) {
      $this->setErrorMessage("Unable to find a mail address for user $username");
      return false;
    }
    if (!$this->setUserToken($this->getUserId($username))) {
      $this->setErrorMessage("Unable to setup token for password reset");
      return false;
    }
    // Send password reset link
    if (!$token = $this->getUserToken($this->getUserId($username))) {
      $this->setErrorMessage("Unable fetch token for password reset");
      return false;
    }
    $smarty->assign('TOKEN', $token);
    $smarty->assign('USERNAME', $username);
    $smarty->assign('WEBSITENAME', $this->config['website']['name']);
    $headers = 'From: Website Administration <' . $this->config['website']['email'] . ">\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    if (mail($email,
      $smarty->fetch('templates/mail/subject.tpl'),
      $smarty->fetch('templates/mail/body.tpl'),
      $headers)) {
        return true;
      } else {
        $this->setErrorMessage("Unable to send mail to your address");
        return false;
      }
    return false;
  }
}

// Make our class available automatically
$user = new User($debug, $mysqli, SALT, $config);
