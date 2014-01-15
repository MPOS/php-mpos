<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

class Payout Extends Base {
  protected $table = 'payouts';

  /**
   * Check if the user has an active payout request already
   * @param account_id int Account ID
   * @return boolean bool True of False
   **/
  public function isPayoutActive($account_id) {
    $stmt = $this->mysqli->prepare("SELECT id FROM $this->table WHERE completed = 0 AND account_id = ? LIMIT 1");
    if ($stmt && $stmt->bind_param('i', $account_id) && $stmt->execute( )&& $stmt->store_result() && $stmt->num_rows > 0)
      return true;
    return $this->sqlError('E0048');
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
    return $this->sqlError('E0050');
  }

  /**
   * Insert a new payout request
   * @param account_id int Account ID
   * @param strToken string Token to confirm
   * @return data mixed Inserted ID or false
   **/
  public function createPayout($account_id=NULL, $strToken) {
    // twofactor - if cashout enabled we need to create/check the token
    if ($this->config['twofactor']['enabled'] && $this->config['twofactor']['withdraw']) {
      $tData = $this->token->getToken($strToken, 'withdraw_funds');
      $tExists = $this->token->doesTokenExist('withdraw_funds', $account_id);
      if (!is_array($tData) && $tExists == false) {
        // token doesn't exist, let's create one, send an email with a link to use it, and error out
        $token = $this->token->createToken('withdraw_funds', $account_id);
        $aData['token'] = $token;
        $aData['username'] = $this->getUserName($account_id);
        $aData['email'] = $this->getUserEmail($aData['username']);
        $aData['subject'] = 'Manual payout request confirmation';
        $this->mail->sendMail('notifications/withdraw_funds', $aData);
        $this->setErrorMessage("A confirmation has been sent to your e-mail");
        return false;
      } else {
        // already exists, if it's valid delete it and allow this edit
        if ($strToken === $tData['token']) {
          $this->token->deleteToken($tData['token']);
        } else {
          // token exists for this type, but this is not the right token
          $this->setErrorMessage("A confirmation was sent to your e-mail, follow that link to cash out");
          return false;
        }
      }
    }
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id) VALUES (?)");
    if ($stmt && $stmt->bind_param('i', $account_id) && $stmt->execute()) {
      return $stmt->insert_id;
    }
    return $this->sqlError('E0049');
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
    return $this->sqlError('E0051');
  }
}

$oPayout = new Payout();
$oPayout->setDebug($debug);
$oPayout->setMysql($mysqli);
$oPayout->setConfig($config);
$oPayout->setMail($mail);
$oPayout->setToken($oToken);
$oPayout->setErrorCodes($aErrorCodes);

?>
