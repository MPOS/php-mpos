<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

class Token Extends Base {
  var $table = 'tokens';

  /**
   * Fetch a token from our table
   * @param name string Setting name
   * @return value string Value
   **/
  public function getToken($strToken) {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE token = ? LIMIT 1");
    if ($stmt && $stmt->bind_param('s', $strToken) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    return false;
  }

  /**
   * Insert a new token
   * @param name string Name of the variable
   * @param value string Variable value
   * @return mixed Token string on success, false on failure
   **/
  public function createToken($strType, $account_id=NULL) {
    $strToken = hash('sha256', $account_id.$strType.microtime());
    if (!$iToken_id = $this->tokentype->getTypeId($strType)) {
      $this->setErrorMessage('Invalid token type: ' . $strType);
      return false;
    }
    $stmt = $this->mysqli->prepare("
      INSERT INTO $this->table (token, type, account_id)
      VALUES (?, ?, ?)
      ");
    if ($stmt && $stmt->bind_param('sii', $strToken, $iToken_id, $account_id) && $stmt->execute())
      return $strToken;
    $this->setErrorMessage('Unable to create new token');
    $this->debug->append('Failed to create new token in database: ' . $this->mysqli->error);
    return false;
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
    return false;
  }
}

$oToken = new Token();
$oToken->setDebug($debug);
$oToken->setMysql($mysqli);
$oToken->setTokenType($tokentype);
