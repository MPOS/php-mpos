<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class Token Extends Base {
  protected $table = 'tokens';

  /**
   * Return time token was created
   * @param id int Token ID
   * @param time string Creation timestamp
   **/
  public function getCreationTime($token) {
    return $this->getSingle($token, 'time', 'token', 's');
  }
  
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
   * Check if a token we're passing in is completely valid
   * @param account_id int Account id of user
   * @param token string Token to check
   * @param type int Type of token
   * @param checkTimeExplicitly Check the token time for expiration; can cause issues w/ timezone & sync
   * @return int 0 or 1
   */
  public function isTokenValid($account_id, $token, $type, $checkTimeExplicitly=false) {
    if (!is_int($account_id) || !is_int($type)) {
      $this->setErrorMessage("Invalid token");
      return 0;
    }
    $expiretime = $this->tokentype->getExpiration($type);
    $ctimedata = new DateTime($this->getCreationTime($token));
    $checktime = $ctimedata->getTimestamp() + $expiretime;
    $now = time();
    if ($checktime >= $now && $checkTimeExplicitly || !$checkTimeExplicitly) {
      if ($checkTimeExplicitly) {
        $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE account_id = ? AND token = ? AND type = ? AND ? >= UNIX_TIMESTAMP() LIMIT 1");
        $stmt->bind_param('isii', $account_id, $token, $type, $checktime);
      } else {
        $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE account_id = ? AND token = ? AND type = ? LIMIT 1");
        $stmt->bind_param('isi', $account_id, $token, $type);
      }
      if ($stmt->execute())
        $res = $stmt->get_result();
        return $res->num_rows;
      return $this->sqlError();
    } else {
      $this->setErrorMessage("Token has expired or is invalid");
      return 0;
    }
  }
  
  /**
   * Check if a token of this type already exists for a given account_id
   * @param strType string Name of the type of token
   * @param account_id int Account id of user to check
   * @return mixed Number of rows on success, false on failure
   */
  public function doesTokenExist($strType=NULL, $account_id=NULL) {
    if (!$iToken_id = $this->tokentype->getTypeId($strType)) {
      $this->setErrorMessage('Invalid token type: ' . $strType);
      return false;
    }
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE account_id = ? AND type = ? LIMIT 1");
    if ($stmt && $stmt->bind_param('ii', $account_id, $iToken_id) && $stmt->execute())
      return $stmt->get_result()->num_rows;
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
