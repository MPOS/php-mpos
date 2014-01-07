<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

class Token Extends Base {
  protected $table = 'tokens';

  /**
   * Fetch a token from our table
   * @param name string Setting name
   * @return value string Value
   **/
  public function getToken($strToken, $strType=NULL) {
    if (empty($strType) || ! $iToken_id = $this->tokentype->getTypeId($strType)) {
      $this->setErrorMessage('Invalid token type: ' . $strType);
      return false;
    }
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE token = ? LIMIT 1");
    if ($stmt && $stmt->bind_param('s', $strToken) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    return $this->sqlError();
  }

  /**
   * Insert a new token
   * @param name string Name of the variable
   * @param value string Variable value
   * @return mixed Token string on success, false on failure
   **/
  public function createToken($strType, $account_id=NULL) {
    if (!$iToken_id = $this->tokentype->getTypeId($strType)) {
      $this->setErrorMessage('Invalid token type: ' . $strType);
      return false;
    }
    $strToken = bin2hex(openssl_random_pseudo_bytes(32));
    $stmt = $this->mysqli->prepare("
      INSERT INTO $this->table (token, type, account_id)
      VALUES (?, ?, ?)
      ");
    if ($stmt && $stmt->bind_param('sii', $strToken, $iToken_id, $account_id) && $stmt->execute())
      return $strToken;
    return $this->sqlError();
  }

 /**
   * Delete a used token
   * @param token string Token name
   * @return bool
   **/
  public function deleteToken($token) {
    $stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE token = ? LIMIT 1");
    if ($stmt && $stmt->bind_param('s', $token) && $stmt->execute())
      return true;
    return $this->sqlError();
  }

  /**
   * Cleanup token table of expired tokens
   * @param none
   * @return bool
   **/
  public function cleanupTokens() {
    // Get all tokens that have an expiration set
    if (!$aTokenTypes = $this->tokentype->getAllExpirations()) {
      // Verbose error for crons since this should not happen
      $this->setCronMessage('Failed to fetch tokens with expiration times: ' . $this->tokentype->getCronError());
      return false;
    }

    $failed = $this->deleted = 0;
    foreach ($aTokenTypes as $aTokenType) {
      $stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE (NOW() - time) > ? AND type = ?");
      if (! ($this->checkStmt($stmt) && $stmt->bind_param('ii', $aTokenType['expiration'], $aTokenType['id']) && $stmt->execute())) {
        $failed++;
      } else {
        $this->deleted += $stmt->affected_rows;
      }
    }
    if ($failed > 0) {
      $this->setCronMessage('Failed to delete ' . $failed . ' token types from ' . $this->table . ' table');
      return false;
    }
    return true;
  }
}

$oToken = new Token();
$oToken->setDebug($debug);
$oToken->setMysql($mysqli);
$oToken->setTokenType($tokentype);
$oToken->setErrorCodes($aErrorCodes);
