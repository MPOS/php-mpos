<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class Token_Type Extends Base {
  protected $table = 'token_types';

  /**
   * Return ID for specific token
   * @param strName string Token Name
   * @return mixed ID on success, false on failure
   **/
  public function getTypeId($strName) {
    return $this->getSingle($strName, 'id', 'name', 's');
  }

  /**
   * Return expiration time for token type
   * @param id int Token ID
   * @param time int Time in seconds for expiration
   **/
  public function getExpiration($id) {
    return $this->getSingle($id, 'expiration', 'id', 'i');
  }

  /**
   * Fetch all tokens that have an expiration set
   * @param none
   * @return array Tokens with expiration times set
   **/
  public function getAllExpirations() {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE expiration > 0");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError();
  }
  
  /**
   * Fetch all tokens - used for unit tests
   * @param none
   * @return array All tokentypes
   **/
  public function getAll() {
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError();
  }
}

$tokentype = new Token_Type();
$tokentype->setDebug($debug);
$tokentype->setMysql($mysqli);
$tokentype->setErrorCodes($aErrorCodes);
