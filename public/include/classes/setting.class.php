<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

class Setting extends Base {
  protected $table = 'settings';

  /**
   * Fetch a value from our table
   * @param name string Setting name
   * @return value string Value
   **/
  public function getValue($name) {
    $stmt = $this->database->prepare("SELECT value FROM $this->table WHERE name = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $name) && $stmt->execute() && $result = $stmt->get_result())
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
   * @return bool
   **/
  public function setValue($name, $value) {
    $stmt = $this->database->prepare("
      INSERT INTO $this->table (name, value)
      VALUES (?, ?)
      ON DUPLICATE KEY UPDATE value = ?");
    if ($stmt && $stmt->bind_param('sss', $name, $value, $value) && $stmt->execute())
      return true;
    return $this->sqlError();
  }
}

$setting = new Setting($debug, $database);
$setting->setDebug($debug);
$setting->setDatabase($database);
$setting->setErrorCodes($aErrorCodes);
