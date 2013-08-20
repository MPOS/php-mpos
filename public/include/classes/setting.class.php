<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Setting {
  public function __construct($debug, $mysqli) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->table = 'settings';
  }

  /**
   * Fetch a value from our table
   * @param name string Setting name
   * @return value string Value
   **/
  public function getValue($name) {
    $query = $this->mysqli->prepare("SELECT value FROM $this->table WHERE name=? LIMIT 1");
    if ($query) {
      $query->bind_param('s', $name);
      $query->execute();
      $query->bind_result($value);
      $query->fetch();
      $query->close();
    } else {
      $this->debug->append("Failed to fetch variable $name from $this->table");
      return false;
    }
    return $value;
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
      ON DUPLICATE KEY UPDATE value = ?
      ");
    if ($stmt && $stmt->bind_param('sss', $name, $value, $value) && $stmt->execute())
      return true;
    $this->debug->append("Failed to set $name to $value");
    return false;
  }
}

$setting = new Setting($debug, $mysqli);
