<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class Setting extends Base {
  protected $table = 'settings';

  /**
   * Fetch a value from our table
   * @param name string Setting name
   * @return value string Value
   **/
  public function getValue($name, $default="") {
    $stmt = $this->mysqli->prepare("SELECT value FROM $this->table WHERE name = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $name) && $stmt->execute() && $result = $stmt->get_result()) {
      if ($result->num_rows > 0) {
        return $result->fetch_object()->value;
      } else {
        return $default;
      }
    }
    // Log error but return empty string
    $this->sqlError();
    return $default;
  }

  /**
   * Insert or update a setting
   * @param name string Name of the variable
   * @param value string Variable value
   * @return bool
   **/
  public function setValue($name, $value) {
    $stmt = $this->mysqli->prepare("
      INSERT INTO $this->table (name, value)
      VALUES (?, ?)
      ON DUPLICATE KEY UPDATE value = ?");
    if ($stmt && $stmt->bind_param('sss', $name, $value, $value) && $stmt->execute())
      return true;
    return $this->sqlError();
  }
}

$setting = new Setting($debug, $mysqli);
$setting->setDebug($debug);
$setting->setMysql($mysqli);
$setting->setErrorCodes($aErrorCodes);
