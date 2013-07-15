<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

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
    $this->setErrorMessage('Unable to fetch invitiations send from your account');
    $this->debug->append('Failed to fetch invitations from database: ' . $this->mysqli->errro);
    return false;
  }

  public function getCountInvitations($account_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT count(id) AS total FROM $this->table WHERE account_id = ?");
    if ($stmt && $stmt->bind_param('i', $account_id) && $stmt->execute() && $stmt->bind_result($total) && $stmt->fetch())
      return $total;
    $this->setErrorMessage('Unable to fetch invitiations send from your account');
    $this->debug->append('Failed to fetch invitations from database: ' . $this->mysqli->errro);
    return false;
  }
  public function getByEmail($strEmail) {
    $this->debug->append("STA " . __METHOD__, 4);
    return $this->getSingle($strEmail, 'id', 'email', 's');
  }

  public function getByTokenId($token_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    return $this->getSingle($token_id, 'id', 'token_id');
  }
  public function setActivated($token_id) {
    if (!$iInvitationId = $this->getByTokenId($token_id)) {
      $this->setErrorMessage('Unable to convert token ID to invitation ID');
      return false;
    }
    $field = array('name' => 'is_activated', 'type' => 'i', 'value' => 1);
    return $this->updateSingle($iInvitationId, $field);
  }
  public function createInvitation($account_id, $email, $token_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table ( account_id, email, token_id ) VALUES ( ?, ?, ?)");
    if ($stmt && $stmt->bind_param('isi', $account_id, $email, $token_id) && $stmt->execute())
      return true;
    return false;
  }
  public function sendInvitation($account_id, $aData) {
    $this->debug->append("STA " . __METHOD__, 4);
    // Check data input
    if (empty($aData['email']) || !filter_var($aData['email'], FILTER_VALIDATE_EMAIL)) {
      $this->setErrorMessage( 'Invalid e-mail address' );
      return false;
    }
    if (preg_match('/[^a-z_\.\!\?\-0-9 ]/i', $aData['message'])) {
      $this->setErrorMessage('Message may only contain alphanumeric characters');
      return false;
    }
    // Ensure this invitation does not exist yet nor do we have an account with that email
    if ($this->user->getEmail($aData['email'])) {
      $this->setErrorMessage('This email is already registered as an account');
      return false;
    }
    if ($this->getByEmail($aData['email'])) {
      $this->setErrorMessage('A pending invitation for this address already exists');
      return false;
    }
    if (!$aData['token'] = $this->token->createToken('invitation', $account_id)) {
      $this->setErrorMessage('Unable to generate invitation token: ' . $this->token->getError());
      return false;
    }
    $aData['username'] = $this->user->getUserName($account_id);
    $aData['subject'] = 'Pending Invitation';
    if ($this->mail->sendMail('invitations/body', $aData)) {
      $aToken = $this->token->getToken($aData['token']);
      if (!$this->createInvitation($account_id, $aData['email'], $aToken['id'])) {
        $this->setErrorMessage('Unable to create invitation record');
        return false;
      }
      return true;
    } else {
      $this->setErrorMessage('Unable to send email to recipient');
    }
    $this->setErrorMessage('Unable to send invitation');
    return false;
  }
}

$invitation = new invitation();
$invitation->setDebug($debug);
$invitation->setMysql($mysqli);
$invitation->setMail($mail);
$invitation->setUser($user);
$invitation->setToken($oToken);
$invitation->setConfig($config);

?>
