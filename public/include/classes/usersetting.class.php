<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class UserSetting extends Base {
  protected $table = 'user_settings';

  /**
   * Fetch a value from our table
   * @param name string Setting name
   * @return value string Value
   **/
  public function getValue($name, $account_id) {
    $stmt = $this->mysqli->prepare("SELECT value FROM $this->table WHERE name = ? AND account_id = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('si', $name, $account_id) && $stmt->execute() && $result = $stmt->get_result())
      if ($result->num_rows > 0)
        return $result->fetch_object()->value;
    // Log error but return empty string
    $this->sqlError();
    return "";
  }

  /**
   * Insert or update a setting
   * @param name string Name of the variable
   * @param value string Variable value
   * @param binary bool Whether or not the value to set is binary
   * @return bool
   **/
  public function setValue($name, $account_id, $value, $binary=false) {
    $value = ($binary) ? (int)$value : $value;
    $value = ($binary && $value > 1 || !is_numeric($value)) ? 1 : $value;
    $value = ($binary && $value < 0 || !is_numeric($value)) ? 0 : $value;
    $stmt = $this->mysqli->prepare("
      INSERT INTO $this->table (name, account_id, value)
      VALUES (?, ?, ?)
      ON DUPLICATE KEY UPDATE value = ?");
    if ($stmt && $stmt->bind_param('siss', $name, $account_id, $value, $value) && $stmt->execute())
      return true;
    return $this->sqlError();
  }
}

$uSetting = new UserSetting($debug, $mysqli);
$uSetting->setDebug($debug);
$uSetting->setMysql($mysqli);
$uSetting->setErrorCodes($aErrorCodes);
