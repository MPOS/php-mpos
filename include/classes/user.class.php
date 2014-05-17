<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class User extends Base {
  protected $table = 'accounts';
  private $userID = false;
  private $user = array();

  // get and set methods
  private function getHash($string, $version=0, $pepper='') {
    switch($version) {
    case 0:
      return hash('sha256', $string.$this->salt);
      break;
    case 1:
      return '$' . $version . '$' . $pepper . '$' . hash('sha256', $string.$this->salt.$pepper);
      break;
    }
  }
  public function getUserName($id) {
    return $this->getSingle($id, 'username', 'id');
  }
  public function getUserNameByEmail($email) {
    return $this->getSingle($email, 'username', 'email', 's');
  }
  public function getUserId($username, $lower=false) {
    return $this->getSingle($username, 'id', 'username', 's', $lower);
  }
  public function getUserIdByEmail($email, $lower=false) {
    return $this->getSingle($email, 'id', 'email', 's', $lower);
  }
  public function getUserEmail($username, $lower=false) {
    return $this->getSingle($username, 'email', 'username', 's', $lower);
  }
  public function getUserEmailById($id) {
    return $this->getSingle($id, 'email', 'id', 'i');
  }
  public function getUserPasswordHashById($id) {
    return $this->getSingle($id, 'pass', 'id', 'i');
  }
  public function getUserPinHashById($id) {
    return $this->getSingle($id, 'pin', 'id', 'i');
  }
  public function getUserNoFee($id) {
    return $this->getSingle($id, 'no_fees', 'id');
  }
  public function getUserDonatePercent($id) {
    return $this->getDonatePercent($id);
  }
  public function getUserAdmin($id) {
    return $this->getSingle($id, 'is_admin', 'id');
  }
  public function getUserLocked($id) {
    return $this->getSingle($id, 'is_locked', 'id');
  }
  public function getUserIp($id) {
    return $this->getSingle($id, 'loggedIp', 'id');
  }
  public function getLastLogin($id) {
    return $this->getSingle($id, 'last_login', 'id');
  }
  public function getEmail($email) {
    return $this->getSingle($email, 'email', 'email', 's');
  }
  public function getUserFailed($id) {
   return $this->getSingle($id, 'failed_logins', 'id');
  }
  public function getUserPinFailed($id) {
   return $this->getSingle($id, 'failed_pins', 'id');
  }
  public function isNoFee($id) {
    return $this->getUserNoFee($id);
  }
  public function isLocked($id) {
    return $this->getUserLocked($id);
  }
  public function isAdmin($id) {
    return $this->getUserAdmin($id);
  }
  public function getSignupTime($id) {
    return $this->getSingle($id, 'signup_timestamp', 'id');
  }
  public function changeNoFee($id) {
    $field = array('name' => 'no_fees', 'type' => 'i', 'value' => !$this->isNoFee($id));
    $this->log->log("warn", $this->getUserName($id)." changed no_fees to ".$this->isNoFee($id));
    return $this->updateSingle($id, $field);
  }
  public function setLocked($id, $value) {
    $field = array('name' => 'is_locked', 'type' => 'i', 'value' => $value);
    $this->log->log("warn", $this->getUserName($id)." changed is_locked to $value");
    return $this->updateSingle($id, $field);
  }
  public function changeAdmin($id) {
    $field = array('name' => 'is_admin', 'type' => 'i', 'value' => !$this->isAdmin($id));
    $this->log->log("warn", $this->getUserName($id)." changed is_admin to ".$this->isAdmin($id));
    return $this->updateSingle($id, $field);
  }
  public function setUserFailed($id, $value) {
    $field = array( 'name' => 'failed_logins', 'type' => 'i', 'value' => $value);
    return $this->updateSingle($id, $field);
  }
  public function setUserPinFailed($id, $value) {
    $field = array( 'name' => 'failed_pins', 'type' => 'i', 'value' => $value);
    return $this->updateSingle($id, $field);
  }
  private function incUserFailed($id) {
    $field = array( 'name' => 'failed_logins', 'type' => 'i', 'value' => $this->getUserFailed($id) + 1);
    return $this->updateSingle($id, $field);
  }
  private function incUserPinFailed($id) {
    $field = array( 'name' => 'failed_pins', 'type' => 'i', 'value' => $this->getUserPinFailed($id) + 1);
    return $this->updateSingle($id, $field);
  }
  private function setUserIp($id, $ip) {
    $field = array( 'name' => 'loggedIp', 'type' => 's', 'value' => $ip );
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
   * Fetch last registered users for administrative tasks
   * @param none
   * @return data array All users with db columns as array fields
   **/
  public function getLastRegisteredUsers($limit=10,$start=0) {
    $this->debug->append("STA " . __METHOD__, 4);
    $invitation = new Invitation();
    $invitation->setMysql($this->mysqli);
    $invitation->setDebug($this->debug);
    $invitation->setLog($this->log);
    $stmt = $this->mysqli->prepare("
    	SELECT a.id,a.username as mposuser,a.email,a.signup_timestamp,u.username AS inviter FROM " . $this->getTableName() . " AS a
    	LEFT JOIN " . $invitation->getTableName() . " AS i
    	ON a.email = i.email
    	LEFT JOIN " . $this->getTableName() . " AS u
    	ON i.account_id = u.id
    	ORDER BY a.id DESC LIMIT ?,?");
    if ($this->checkStmt($stmt) && $stmt->bind_param("ii", $start, $limit) && $stmt->execute() && $result = $stmt->get_result()) {
      return $result->fetch_all(MYSQLI_ASSOC);
    }
  }

  /**
   * Fetch Top 10 Inviters
   * @param none
   * @return data array All users with db columns as array fields
   **/
  public function getTopInviters($limit=10,$start=0) {
    $this->debug->append("STA " . __METHOD__, 4);
    $invitation = new Invitation();
    $invitation->setMysql($this->mysqli);
    $invitation->setDebug($this->debug);
    $invitation->setLog($this->log);
    $stmt = $this->mysqli->prepare("
    	SELECT COUNT(i.account_id) AS invitationcount,a.id,a.username,a.email,    	
    	(SELECT COUNT(account_id) FROM " . $invitation->getTableName() . " WHERE account_id = i.account_id AND is_activated = 1 GROUP BY account_id) AS activated
    	FROM " . $invitation->getTableName() . " AS i
    	LEFT JOIN " . $this->getTableName() . " AS a
    	ON a.id = i.account_id
    	GROUP BY i.account_id
    	ORDER BY invitationcount ASC
    	LIMIT ?,?");
    if ($this->checkStmt($stmt) && $stmt->bind_param("ii", $start, $limit) && $stmt->execute() && $result = $stmt->get_result()) {
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
    if (empty($username) || empty($password)) {
      $this->setErrorMessage("Invalid username or password.");
      return false;
    }
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
      $this->debug->append("Not an e-mail address, rejecting login", 2);
      $this->setErrorMessage("Please login with your e-mail address");
      return false;
    } else {
      $this->debug->append("Username is an e-mail: $username", 2);
      if (!$username = $this->getUserNameByEmail($username)) {
        $this->setErrorMessage("Invalid username or password.");
        return false;
      }
    }
    if ($this->isLocked($this->getUserId($username))) {
      $this->setErrorMessage('Account locked. Please Check your Email for instructions to unlock.');
      return false;
    }
    if ($this->checkUserPassword($username, $password)) {
      // delete notification cookies
      setcookie("motd-box", "", time()-3600);
      setcookie("lastlogin-box", "", time()-3600);
      setcookie("backend-box", "", time()-3600);
      // rest of login process
      $uid = $this->getUserId($username);
      $lastLoginTime = $this->getLastLogin($uid);
      $this->updateLoginTimestamp($uid);
      $getIPAddress = $this->getUserIp($uid);
      if ($getIPAddress !== $this->getCurrentIP()) {
        $this->log->log("warn", "$username has logged in with a different IP, saved is [$getIPAddress]");
      }
      $setIPAddress = $this->setUserIp($uid, $_SERVER['REMOTE_ADDR']);
      $this->createSession($username, $getIPAddress, $lastLoginTime);
      if ($setIPAddress) {
        // send a notification if success_login is active
        $uid = $this->getUserId($username);
        $notifs = new Notification();
        $notifs->setDebug($this->debug);
        $notifs->setMysql($this->mysqli);
        $notifs->setSmarty($this->smarty);
        $notifs->setConfig($this->config);
        $notifs->setSetting($this->setting);
        $notifs->setErrorCodes($this->aErrorCodes);
        $ndata = $notifs->getNotificationSettings($uid);
        if (@$ndata['success_login'] == 1) {
          // seems to be active, let's send it
          $aDataN['username'] = $username;
          $aDataN['email'] = $this->getUserEmail($username);
          $aDataN['subject'] = 'Successful login notification';
          $aDataN['LOGINIP'] = $this->getCurrentIP();
          $aDataN['LOGINUSER'] = $username;
          $aDataN['LOGINTIME'] = date('m/d/y H:i:s');
          $notifs->sendNotification($uid, 'success_login', $aDataN);
        }
        return true;
      }
    }
    $this->setErrorMessage("Invalid username or password");
    $this->log->log('error', "Authentication failed for $username");
    if ($id = $this->getUserId($username)) {
      $this->incUserFailed($id);
      // Check if this account should be locked
      if (isset($this->config['maxfailed']['login']) && $this->getUserFailed($id) >= $this->config['maxfailed']['login']) {
        $this->setLocked($id, 1);
        $this->log->log("warn", "$username locked due to failed logins, saved is [".$this->getUserIp($this->getUserId($username))."]");
        if ($token = $this->token->createToken('account_unlock', $id)) {
          $aData['token'] = $token;
          $aData['username'] = $username;
          $aData['email'] = $this->getUserEmail($username);
          $aData['subject'] = 'Account auto-locked';
          $this->mail->sendMail('notifications/locked', $aData);
        }
      }
    }

    return false;
  }

  /**
   * Check the users PIN for confirmation
   * @param userID int User ID
   * @param pin int PIN to check
   * @return bool
   **/
  public function checkPin($userId, $pin='') {
    $this->debug->append("STA " . __METHOD__, 4);
    $this->debug->append("Confirming PIN for $userId and pin $pin", 2);
    $strPinHash = $this->getUserPinHashById($userId);
    $aPin = explode('$', $strPinHash);
    count($aPin) == 1 ? $pin_hash = $this->getHash($pin, 0) : $pin_hash = $this->getHash($pin, $aPin[1], $aPin[2]);
    $stmt = $this->mysqli->prepare("SELECT pin FROM $this->table WHERE id = ? AND pin = ? LIMIT 1");
    if ($stmt->bind_param('is', $userId, $pin_hash) && $stmt->execute() && $stmt->bind_result($row_pin) && $stmt->fetch()) {
      $this->setUserPinFailed($userId, 0);
      return ($pin_hash === $row_pin);
    }
    $this->log->log('info', $this->getUserName($userId).' incorrect pin');
    $this->incUserPinFailed($userId);
    // Check if this account should be locked
    if (isset($this->config['maxfailed']['pin']) && $this->getUserPinFailed($userId) >= $this->config['maxfailed']['pin']) {
      $this->setLocked($userId, 1);
      $this->log->log("warn", $this->getUserName($userId)." was locked due to incorrect pins");
      if ($token = $this->token->createToken('account_unlock', $userId)) {
        $username = $this->getUserName($userId);
        $aData['token'] = $token;
        $aData['username'] = $username;
        $aData['email'] = $this->getUserEmail($username);
        $aData['subject'] = 'Account auto-locked';
        $this->mail->sendMail('notifications/locked', $aData);
      }
      $this->logoutUser();
    }
    return false;
  }

  public function generatePin($userID, $current) {
    $this->debug->append("STA " . __METHOD__, 4);
    $username = $this->getUserName($userID);
    $email = $this->getUserEmail($username);
    $strPasswordHash = $this->getUserPasswordHashById($userID);
    $aPassword = explode('$', $strPasswordHash);
    count($aPassword) == 1 ? $password_hash = $this->getHash($current, 0) : $password_hash = $this->getHash($current, $aPassword[1], $aPassword[2]);
    $newpin = intval( '0' . rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9) );
    $aData['username'] = $username;
    $aData['email'] = $email;
    $aData['pin'] = $newpin;
    $newpin = $this->getHash($newpin, HASH_VERSION, bin2hex(openssl_random_pseudo_bytes(32)));
    $aData['subject'] = 'PIN Reset Request';
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET pin = ? WHERE ( id = ? AND pass = ? )");
    if ($this->checkStmt($stmt) && $stmt->bind_param('sis', $newpin, $userID, $password_hash) && $stmt->execute()) {
      if ($stmt->errno == 0 && $stmt->affected_rows === 1) {
        if ($this->mail->sendMail('pin/reset', $aData)) {
          $this->log->log("info", "$username was sent a pin reset e-mail");
          return true;
        } else {
          $this->log->log("warn", "$username request a pin reset but failed to send mail");
          $this->setErrorMessage('Unable to send mail to your address');
          return false;
        }
      }
    }
    $this->log->log("warn", "$username incorrect pin reset attempt");
    $this->setErrorMessage( 'Unable to generate PIN, current password incorrect?' );
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
   * Check if a coin address exists already
   * @param address string Coin Address
   * @return bool True of false
   **/
  public function existsCoinAddress($address) {
    $this->debug->append("STA " . __METHOD__, 4);
    return $this->getSingle($address, 'coin_address', 'coin_address', 's') === $address;
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
   * Send e-mail to confirm a change for 2fa
   * @param strType string Token type name
   * @param userID int User ID
   * @return bool
   */
  public function sendChangeConfigEmail($strType, $userID) {
    $exists = $this->token->doesTokenExist($strType, $userID);
    if ($exists == 0) {
      $token = $this->token->createToken($strType, $userID);
      $aData['token'] = $token;
      $aData['username'] = $this->getUserName($userID);
      $aData['email'] = $this->getUserEmail($aData['username']);
      switch ($strType) {
      	case 'account_edit':
      	  $aData['subject'] = 'Account detail change confirmation';
      	  break;
      	case 'change_pw':
      	  $aData['subject'] = 'Account password change confirmation';
      	  break;
      	case 'withdraw_funds':
      	  $aData['subject'] = 'Manual payout request confirmation';
      	  break;
      	default:
      	  $aData['subject'] = '';
      }
      $this->log->log("info", $aData['username']." was sent a $strType token e-mail");
      if ($this->mail->sendMail('notifications/'.$strType, $aData)) {
        return true;
      } else {
        $this->setErrorMessage('Failed to send the notification');
        $this->log->log("warn", $aData['username']." requested a $strType token but sending mail failed");
        return false;
      }
    }
    $this->log->log("warn", $this->getUserName($userID)." attempted to request multiple $strType tokens");
    $this->setErrorMessage('A request has already been sent to your e-mail address. Please wait an hour for it to expire.');
    return false;
  }

  /**
   * Update the accounts password
   * @param userID int User ID
   * @param current string Current password
   * @param new1 string New password
   * @param new2 string New password confirmation
   * @param strToken string Token for confirmation
   * @return bool
   **/
  public function updatePassword($userID, $current, $new1, $new2, $strToken) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($new1 !== $new2) {
      $this->setErrorMessage( 'New passwords do not match' );
      return false;
    }
    if ( strlen($new1) < 8 ) {
      $this->setErrorMessage( 'New password is too short, please use more than 8 chars' );
      return false;
    }
    $strPasswordHash = $this->getUserPasswordHashById($userID);
    $aPassword = explode('$', $strPasswordHash);
    count($aPassword) == 1 ? $password_hash = $this->getHash($current, 0) : $password_hash = $this->getHash($current, $aPassword[1], $aPassword[2]);
    $new = $this->getHash($new1, HASH_VERSION, bin2hex(openssl_random_pseudo_bytes(32)));
    if ($this->config['twofactor']['enabled'] && $this->config['twofactor']['options']['changepw']) {
      $tValid = $this->token->isTokenValid($userID, $strToken, 6);
      if ($tValid) {
        if ($this->token->deleteToken($strToken)) {
          $this->log->log("info", $this->getUserName($userID)." deleted change password token");
          // token deleted, continue
        } else {
          $this->log->log("warn", $this->getUserName($userID)." failed to delete the change password token");
          $this->setErrorMessage('Token deletion failed');
          return false;
        }
      } else {
        $this->log->log("error", $this->getUserName($userID)." attempted to use an invalid change password token");
        $this->setErrorMessage('Invalid token');
        return false;
      }
    }
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET pass = ? WHERE ( id = ? AND pass = ? )");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('sis', $new, $userID, $password_hash);
      $stmt->execute();
      if ($stmt->errno == 0 && $stmt->affected_rows === 1) {
        $this->log->log("info", $this->getUserName($userID)." updated password");
        return true;
      }
      $stmt->close();
    }
    $this->log->log("warn", $this->getUserName($userID)." incorrect password update attempt");
    $this->setErrorMessage( 'Unable to update password, current password wrong?' );
    return false;
  }

  /**
   * Update account information from the edit account page
   * @param userID int User ID
   * @param address string new coin address
   * @param threshold float auto payout threshold
   * @param donat float donation % of income
   * @param strToken string Token for confirmation
   * @return bool
   **/
  public function updateAccount($userID, $address, $threshold, $donate, $email, $timezone, $is_anonymous, $strToken) {
    $this->debug->append("STA " . __METHOD__, 4);
    $bUser = false;
    $donate = round($donate, 2);
    // number validation checks
    if (!is_numeric($threshold)) {
      $this->setErrorMessage('Invalid input for auto-payout');
      return false;
    } else if ($threshold < $this->config['ap_threshold']['min'] && $threshold != 0) {
      $this->setErrorMessage('Threshold below configured minimum of ' . $this->config['ap_threshold']['min']);
      return false;
    } else if ($threshold > $this->config['ap_threshold']['max']) {
      $this->setErrorMessage('Threshold above configured maximum of ' . $this->config['ap_threshold']['max']);
      return false;
    }
    if (!is_numeric($donate)) {
      $this->setErrorMessage('Invalid input for donation');
      return false;
    } else if ($donate < $this->config['donate_threshold']['min'] && $donate != 0) {
      $this->setErrorMessage('Donation below allowed ' . $this->config['donate_threshold']['min'] . '% limit');
      return false;
    } else if ($donate > 100) {
      $this->setErrorMessage('Donation above allowed 100% limit');
      return false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->setErrorMessage('Invalid email address');
      return false;
    }
    if (!empty($address)) {
      if ($address != $this->getCoinAddress($userID) && $this->existsCoinAddress($address)) {
        $this->setErrorMessage('Address is already in use');
        return false;
      }
      if ($this->bitcoin->can_connect() === true) {
        if (!$this->bitcoin->validateaddress($address)) {
          $this->setErrorMessage('Invalid coin address');
          return false;
        }
      } else {
        $this->setErrorMessage('Unable to connect to RPC server for coin address validation');
        return false;
      }
    } else {
      $address = NULL;
    }

    // Number sanitizer, just in case we fall through above
    $threshold = min($this->config['ap_threshold']['max'], max(0, floatval($threshold)));
    $donate = min(100, max(0, floatval($donate)));

    // twofactor - consume the token if it is enabled and valid
    if ($this->config['twofactor']['enabled'] && $this->config['twofactor']['options']['details']) {
      $tValid = $this->token->isTokenValid($userID, $strToken, 5);
      if ($tValid) {
        if ($this->token->deleteToken($strToken)) {
          $this->log->log("info", $this->getUserName($userID)." deleted account update token");
        } else {
          $this->setErrorMessage('Token deletion failed');
          $this->log->log("warn", $this->getUserName($userID)." updated their account details but failed to delete token");
          return false;
        }
      } else {
        $this->setErrorMessage('Invalid token');
        $this->log->log("warn", $this->getUserName($userID)." attempted to use an invalid token account update token");
        return false;
      }
    }

    // We passed all validation checks so update the account
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET coin_address = ?, ap_threshold = ?, donate_percent = ?, email = ?, timezone = ?, is_anonymous = ? WHERE id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('sddssii', $address, $threshold, $donate, $email, $timezone, $is_anonymous, $userID) && $stmt->execute()) {
      $this->log->log("info", $this->getUserName($userID)." updated their account details");
      return true;
    }
    // Catchall
    $this->setErrorMessage('Failed to update your account');
    $this->debug->append('Account update failed: ' . $this->mysqli->error);
    return false;
  }

  /**
   * Check API key for authentication
   * @param key string API key hash
   * @return bool
   **/
  public function checkApiKey($key) {
    $this->debug->append("STA " . __METHOD__, 4);
    if (!is_string($key)) return false;
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
    $stmt = $this->mysqli->prepare("SELECT username, pass, id, timezone, is_admin FROM $this->table WHERE LOWER(username) = LOWER(?) LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $username) && $stmt->execute() && $stmt->bind_result($row_username, $row_password, $row_id, $row_timezone, $row_admin)) {
      $stmt->fetch();
      $stmt->close();
      $aPassword = explode('$', $row_password);
      count($aPassword) == 1 ? $password_hash = $this->getHash($password, 0) : $password_hash = $this->getHash($password, $aPassword[1], $aPassword[2]);
      // Store the basic login information
      $this->user = array('username' => $row_username, 'id' => $row_id, 'timezone' => $row_timezone, 'is_admin' => $row_admin);
      return $password_hash === $row_password && strtolower($username) === strtolower($row_username);
    }
    return $this->sqlError();
  }

  /**
   * Create a PHP session for a user
   * @param username string Username to create session for
   * @return none
   **/
  private function createSession($username, $lastIP='', $lastLoginTime='') {
    $this->debug->append("STA " . __METHOD__, 4);
    $this->debug->append("Log in user to _SESSION", 2);
    if (!empty($lastIP) && (!empty($lastLoginTime))) {
      $_SESSION['last_ip_pop'] = array($lastIP, $lastLoginTime);
    }
    session_regenerate_id(true);
    $_SESSION['AUTHENTICATED'] = '1';
    // $this->user from checkUserPassword
    $_SESSION['USERDATA'] = $this->user;
    if ($this->config['protect_session_state']) {
      $_SESSION['STATE'] = md5($_SESSION['USERDATA']['username'].$_SESSION['USERDATA']['id'].@$_SERVER['HTTP_USER_AGENT']);
    }
  }

  /**
   * Update users last_login timestamp
   * @param id int UserID
   * @return bool true of false
   **/
  private function updateLoginTimestamp($id) {
    $field = array('name' => 'last_login', 'type' => 'i', 'value' => time());
    return $this->updateSingle($id, $field);
  }

  /**
   * Log out current user, destroy the session
   * @param none
   * @return true
   **/
  public function logoutUser() {
    $this->debug->append("STA " . __METHOD__, 4);
    // Unset all of the session variables
    $_SESSION = array();
    // As we're killing the sesison, also kill the cookie!
    setcookie(session_name(), '', time() - 42000);
    // Destroy the session.
    session_destroy();
    // Enforce generation of a new Session ID and delete the old
    session_regenerate_id(true);

    // Enforce a page reload and point towards login with referrer included, if supplied
    $port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : (":".$_SERVER["SERVER_PORT"]);
    $pushto = $_SERVER['SCRIPT_NAME'].'?page=login';
    $location = (@$_SERVER['HTTPS'] == 'on') ? 'https://' . $_SERVER['SERVER_NAME'] . $port . $pushto : 'http://' . $_SERVER['SERVER_NAME'] . $port . $pushto;
    if (!headers_sent()) header('Location: ' . $location);
    exit('<meta http-equiv="refresh" content="0; url=' . $location . '"/>');
  }

  /**
   * Get all users for admin panel
   **/
  public function getAllUsers($filter='%') {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
      a.id AS id,
      a.username AS username
      FROM " . $this->getTableName() . " AS a
      WHERE a.username LIKE ?
      GROUP BY username");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $filter) && $stmt->execute() && $result = $stmt->get_result()) {
      while ($row = $result->fetch_assoc()) {
        $aData[$row['id']] = $row['username'];
      }
      return $aData;
    }
    return false;
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
      id, username, pin, api_key, is_admin, is_anonymous, email, timezone, no_fees,
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
  public function register($username, $coinaddress, $password1, $password2, $pin, $email1='', $email2='', $tac='', $strToken='') {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($tac != 1) {
      $this->setErrorMessage('You need to accept our <a href="'.$_SERVER['SCRIPT_NAME'].'?page=tac" target="_blank">Terms and Conditions</a>');
      return false;
    }
    if (strlen($username) > 40) {
      $this->setErrorMessage('Username exceeding character limit');
      return false;
    }
    if (!$this->bitcoin->validateaddress($coinaddress)) {
      $this->setErrorMessage('Coin address is not valid');
      return false;
    }
    if (preg_match('/[^a-z_\-0-9]/i', $username)) {
      $this->setErrorMessage('Username may only contain alphanumeric characters');
      return false;
    }
    if ($this->getEmail($email1)) {
      $this->setErrorMessage( 'This e-mail address is already taken' );
      return false;
    }
    if (strlen($password1) < 8) { 
      $this->setErrorMessage( 'Password is too short, minimum of 8 characters required' );
      return false;
    }
    if ($password1 !== $password2) {
      $this->setErrorMessage( 'Password do not match' );
      return false;
    }
    if (empty($email1) || !filter_var($email1, FILTER_VALIDATE_EMAIL)) {
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
    if (isset($strToken) && !empty($strToken)) {
      if ( ! $aToken = $this->token->getToken($strToken, 'invitation')) {
        $this->setErrorMessage('Unable to find token');
        return false;
      }
      // Circle dependency, so we create our own object here
      $invitation = new Invitation();
      $invitation->setMysql($this->mysqli);
      $invitation->setDebug($this->debug);
      $invitation->setLog($this->log);
      $invitation->setUser($this);
      $invitation->setConfig($this->config);
      if (!$invitation->setActivated($aToken['id'])) {
        $this->setErrorMessage('Unable to activate your invitation');
        return false;
      }
      if (!$this->token->deleteToken($strToken)) {
        $this->setErrorMessage('Unable to remove used token');
        $this->log->log("warn", "$username tried to register but failed to delete the invitation token");
        return false;
      }
    }
    if ($this->mysqli->query("SELECT id FROM $this->table LIMIT 1")->num_rows > 0) {
      ! $this->setting->getValue('accounts_confirm_email_disabled') ? $is_locked = 1 : $is_locked = 0;
      $is_admin = 0;
      $stmt = $this->mysqli->prepare("
        INSERT INTO $this->table (username, pass, email, signup_timestamp, pin, api_key, is_locked, coin_address)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
    } else {
      $is_locked = 0;
      $is_admin = 1;
      $stmt = $this->mysqli->prepare("
        INSERT INTO $this->table (username, pass, email, signup_timestamp, pin, api_key, is_admin, is_locked, coin_address)
        VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?)
        ");
    }

    // Create hashed strings using original string and salt
    $password_hash = $this->getHash($password1, HASH_VERSION, bin2hex(openssl_random_pseudo_bytes(32)));
    $pin_hash = $this->getHash($pin, HASH_VERSION, bin2hex(openssl_random_pseudo_bytes(32)));
    $apikey_hash = $this->getHash($username, 0);
    $username_clean = strip_tags($username);
    $signup_time = time();

    if ($this->checkStmt($stmt) && $stmt->bind_param('sssissis', $username_clean, $password_hash, $email1, $signup_time, $pin_hash, $apikey_hash, $is_locked, $coinaddress) && $stmt->execute()) {
      if (! $this->setting->getValue('accounts_confirm_email_disabled') && $is_admin != 1) {
        if ($token = $this->token->createToken('confirm_email', $stmt->insert_id)) {
          $aData['username'] = $username_clean;
          $aData['token'] = $token;
          $aData['email'] = $email1;
          $aData['subject'] = 'E-Mail verification';
          if (!$this->mail->sendMail('register/confirm_email', $aData)) {
            $this->setErrorMessage('Unable to request email confirmation: ' . $this->mail->getError());
            return false;
          }
          return true;
        } else {
          $this->setErrorMessage('Failed to create confirmation token');
          $this->debug->append('Unable to create confirm_email token: ' . $this->token->getError());
          return false;
        }
      } else {
        return true;
      }
    } else {
      $this->setErrorMessage( 'Unable to register' );
      $this->debug->append('Failed to insert user into DB: ' . $this->mysqli->error);
      if ($stmt->sqlstate == '23000') $this->setErrorMessage( 'Username or email already registered' );
      return false;
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
  public function resetPassword($token, $new1, $new2) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($aToken = $this->token->getToken($token, 'password_reset')) {
      if ($new1 !== $new2) {
        $this->setErrorMessage( 'New passwords do not match' );
        return false;
      }
      if ( strlen($new1) < 8 ) { 
        $this->setErrorMessage( 'New password is too short, please use more than 8 chars' );
        return false;
      }
      $new_hash = $this->getHash($new1, HASH_VERSION, bin2hex(openssl_random_pseudo_bytes(32)));
      $stmt = $this->mysqli->prepare("UPDATE $this->table SET pass = ? WHERE id = ?");
      if ($this->checkStmt($stmt) && $stmt->bind_param('si', $new_hash, $aToken['account_id']) && $stmt->execute() && $stmt->affected_rows === 1) {
        if ($this->token->deleteToken($aToken['token'])) {
          return true;
        } else {
          $this->setErrorMessage('Unable to invalidate used token');
        }
      } else {
        $this->setErrorMessage('Unable to set new password or you chose the same password. Please use a different one.');
      }
    } else {
      $this->setErrorMessage('Invalid token: ' . $this->token->getError());
    }
    $this->debug->append('Failed to update password:' . $this->mysqli->error);
    return false;
  }

  /**
   * Reset a password by sending a password reset mail
   * @param username string Username to reset password for
   * @return bool
   **/
  public function initResetPassword($username) {
    $this->debug->append("STA " . __METHOD__, 4);
    // Fetch the users mail address
    if (empty($username)) {
      $this->setErrorMessage("Username must not be empty");
      return false;
    }
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
      $this->debug->append("Username is an e-mail: $username", 2);
      if (!$username = $this->getUserNameByEmail($username)) {
        $this->setErrorMessage("Invalid username or password.");
        return false;
      }
    }
    if (!$aData['email'] = $this->getUserEmail($username, true)) {
      $this->setErrorMessage("Please check your mail account to finish your password reset");
      return false;
    }
    if (!$aData['token'] = $this->token->createToken('password_reset', $this->getUserId($username, true))) {
      $this->setErrorMessage('Unable to setup token for password reset');
      return false;
    }
    $aData['username'] = $this->getUserName($this->getUserId($username, true));
    $aData['subject'] = 'Password Reset Request';
    if ($_SERVER['REMOTE_ADDR'] !== $this->getUserIp($this->getUserId($username, true))) {
      $this->log->log("warn", "$username requested password reset, saved IP is [".$this->getUserIp($this->getUserId($username, true))."]");
    } else {
      $this->log->log("info", "$username requested password reset, saved IP is [".$this->getUserIp($this->getUserId($username, true))."]");
    }
    if ($this->mail->sendMail('password/reset', $aData)) {
        return true;
      } else {
        $this->setErrorMessage('Unable to send mail to your address');
        return false;
      }
    return false;
  }

  /**
   * Check if a user is authenticated and allowed to login
   * Checks the $_SESSION for existing data
   * Destroys the session if account is now locked
   * @param none
   * @return bool
   **/
public function isAuthenticated($logout=true) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ( @$_SESSION['AUTHENTICATED'] == true &&
         !$this->isLocked($_SESSION['USERDATA']['id']) &&
         $this->getUserIp($_SESSION['USERDATA']['id']) == $_SERVER['REMOTE_ADDR'] &&
         ( ! $this->config['protect_session_state'] ||
           (
             $this->config['protect_session_state'] && $_SESSION['STATE'] == md5($_SESSION['USERDATA']['username'].$_SESSION['USERDATA']['id'].@$_SERVER['HTTP_USER_AGENT'])
           )
         )
    ) return true;
    // Catchall
    $this->log->log('warn', 'Forcing logout, user is locked or IP changed mid session [hijack attempt?]');
    if ($logout == true) $this->logoutUser();
    return false;
  }

  /**
   * Convenience function to get IP address, no params is the same as REMOTE_ADDR
   * @param trustremote bool must be FALSE to checkcloudflare, checkclient or checkforwarded
   * @param checkcloudflare bool check HTTP_CF_CONNECTING_IP for a valid ip first
   * @param checkclient bool check HTTP_CLIENT_IP for a valid ip first
   * @param checkforwarded bool check HTTP_X_FORWARDED_FOR for a valid ip first
   * @return string IP address
   */
  public function getCurrentIP($trustremote=false, $checkcloudflare=true, $checkclient=false, $checkforwarded=true) {
    $cf = (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : false;
    $client = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : false;
    $fwd = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : false;
    $remote = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : @$_SERVER['REMOTE_ADDR'];
    // shared internet
    if (!$trustremote && $checkcloudflare && filter_var($cf, FILTER_VALIDATE_IP)) {
      // cloudflare
      return $cf;
    } else if (!$trustremote && $checkclient && filter_var($client, FILTER_VALIDATE_IP)) {
      return $client;
    } else if (!$trustremote && $checkforwarded && strpos($fwd, ',') !== false) {
      // multiple proxies
      $ips = explode(',', $fwd);
      return $ips[0];
    } else if (!$trustremote && $checkforwarded && filter_var($fwd, FILTER_VALIDATE_IP)) {
      // single
      return $fwd;
    } else {
      // as usual
      return $remote;
    }
  }
}

// Make our class available automatically
$user = new User();
$user->setDebug($debug);
$user->setLog($log);
$user->setMysql($mysqli);
$user->setSalt($config['SALT']);
$user->setSmarty($smarty);
$user->setConfig($config);
$user->setMail($mail);
$user->setToken($oToken);
$user->setBitcoin($bitcoin);
$user->setSetting($setting);
$user->setErrorCodes($aErrorCodes);
