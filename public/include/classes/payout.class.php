<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

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
   * Insert a new payout request
   * @param account_id int Account ID
   * @param strToken string Token to confirm
   * @return data mixed Inserted ID or false
   **/
  public function createPayout($account_id=NULL, $strToken) {
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id) VALUES (?)");
    if ($stmt && $stmt->bind_param('i', $account_id) && $stmt->execute()) {
      $insert_id = $stmt->insert_id;
      // twofactor - consume the token if it is enabled and valid
      if ($this->config['twofactor']['enabled'] && $this->config['twofactor']['options']['withdraw']) {
        $tValid = $this->token->isTokenValid($account_id, $strToken, 7);
        if ($tValid) {
          $delete = $this->token->deleteToken($strToken);
          if (!$delete) {
            $this->log->log("info", "User $account_id requested manual payout but failed to delete payout token");
            $this->setErrorMessage('Unable to delete token');
            return false;
          }
        } else {
          $this->log->log("info", "User $account_id requested manual payout using an invalid payout token");
          $this->setErrorMessage('Invalid token');
          return false;
        }
      }
      return $insert_id;
    }
    return $this->sqlError('E0049');
  }

  /**
   * Mark a payout as processed
   * @param id int Payout ID
   * @return boolean bool True or False
   **/
  public function setProcessed($id) {
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET completed = 1 WHERE id = ? LIMIT 1");
    if ($stmt && $stmt->bind_param('i', $id) && $stmt->execute())
      return true;
    return $this->sqlError('E0051');
  }
}

$oPayout = new Payout();
$oPayout->setDebug($debug);
$oPayout->setLog($log);
$oPayout->setMysql($mysqli);
$oPayout->setConfig($config);
$oPayout->setToken($oToken);
$oPayout->setErrorCodes($aErrorCodes);

?>
