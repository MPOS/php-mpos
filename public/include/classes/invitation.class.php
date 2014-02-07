<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class Invitation extends Base {
  var $table = 'invitations';

  /**
   * Fetch invitations for one account
   * @param account_id int Account ID
   * @return mixed Array on success, bool on failure
   **/
  public function getInvitations($account_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE account_id = ?");
    if ($stmt && $stmt->bind_param('i', $account_id) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    $this->sqlError('E0021');
  }

  /**
   * Count invitations sent by an account_id
   * @param account_id integer Account ID
   * @return mixes Integer on success, boolean on failure
   **/
  public function getCountInvitations($account_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT count(id) AS total FROM $this->table WHERE account_id = ?");
    if ($stmt && $stmt->bind_param('i', $account_id) && $stmt->execute() && $stmt->bind_result($total) && $stmt->fetch())
      return $total;
    $this->sqlError('E0021');
  }

  /**
   * Get a specific invitation by email address
   * Used to ensure no invitation was already sent
   * @param strEmail string Email address to check for
   * @return bool boolean true of ralse
   **/
  public function getByEmail($strEmail) {
    $this->debug->append("STA " . __METHOD__, 4);
    return $this->getSingle($strEmail, 'id', 'email', 's');
  }

  /**
   * Get a specific token by token ID
   * Used to match an invitation against a token
   * @param token_id integer Token ID stored in invitation
   * @return data mixed Invitation ID on success, false on error
   **/
  public function getByTokenId($token_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    return $this->getSingle($token_id, 'id', 'token_id');
  }

  /**
   * Set an invitation as activated by the invitee
   * @param token_id integer Token to activate
   * @return bool boolean true or false
   **/
  public function setActivated($token_id) {
    if (!$iInvitationId = $this->getByTokenId($token_id)) {
      $this->setErrorMessage($this->getErrorMsg('E0030'));
      return false;
    }
    $field = array('name' => 'is_activated', 'type' => 'i', 'value' => 1);
    return $this->updateSingle($iInvitationId, $field);
  }

  /**
   * Insert a new invitation to the database
   * @param account_id integer Account ID to bind the invitation to
   * @param email string Email address the invite was sent to
   * @param token_id integer Token ID used during invitation
   * @return bool boolean True of false
   **/
  public function createInvitation($account_id, $email, $token_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table ( account_id, email, token_id ) VALUES ( ?, ?, ?)");
    if ($stmt && $stmt->bind_param('isi', $account_id, $email, $token_id) && $stmt->execute())
      return true;
    $this->sqlError('E0022');
  }

  /**
   * Send an invitation out to a user
   * Uses the mail class to send mails
   * @param account_id integer Sending account ID
   * @param aData array Data array including mail information
   * @return bool boolean True or false
   **/
  public function sendInvitation($account_id, $aData) {
    $this->debug->append("STA " . __METHOD__, 4);
    // Check data input
    if (empty($aData['email']) || !filter_var($aData['email'], FILTER_VALIDATE_EMAIL)) {
      $this->setErrorMessage($this->getErrorMsg('E0023'));
      return false;
    }
    if (preg_match('/[^a-z_\.\!\?\-0-9 ]/i', $aData['message'])) {
      $this->setErrorMessage($this->getErrorMsg('E0024'));
      return false;
    }
    // Ensure this invitation does not exist yet nor do we have an account with that email
    if ($this->user->getEmail($aData['email'])) {
      $this->setErrorMessage($this->getErrorMsg('E0025'));
      return false;
    }
    if ($this->getByEmail($aData['email'])) {
      $this->setErrorMessage($this->getErrorMsg('E0026'));
      return false;
    }
    if (!$aData['token'] = $this->token->createToken('invitation', $account_id)) {
      $this->setErrorMessage($this->getErrorMsg('E0027', $this->token->getError()));
      return false;
    }
    $aData['username'] = $this->user->getUserName($account_id);
    $aData['subject'] = 'Pending Invitation';
    $this->log->log("info", $this->user->getUserName($account_id)." sent an invitation");
    if ($this->mail->sendMail('invitations/body', $aData)) {
      $aToken = $this->token->getToken($aData['token'], 'invitation');
      if (!$this->createInvitation($account_id, $aData['email'], $aToken['id']))
        return false;
      return true;
    } else {
      $this->log->log("warn", $this->user->getUserName($account_id)." sent an invitation but failed to send e-mail");
      $this->setErrorMessage($this->getErrorMsg('E0028'));
    }
    $this->setErrorMessage($this->getErrorMsg('E0029'));
    return false;
  }
}

// Instantiate class
$invitation = new invitation();
$invitation->setDebug($debug);
$invitation->setLog($log);
$invitation->setMysql($mysqli);
$invitation->setMail($mail);
$invitation->setUser($user);
$invitation->setToken($oToken);
$invitation->setConfig($config);
$invitation->setErrorCodes($aErrorCodes);
?>
