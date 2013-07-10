<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Monitoring {
  public function __construct($debug, $mysqli) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->table = 'monitoring';
  }

  /**
   * Fetch a value from our table
   * @param name string Setting name
   * @return value string Value
   **/
  public function getStatus($name) {
    $query = $this->mysqli->prepare("SELECT * FROM $this->table WHERE name = ? LIMIT 1");
    if ($query && $query->bind_param('s', $name) && $query->execute() && $result = $query->get_result()) {
      return $result->fetch_assoc();
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
  public function setStatus($name, $type, $value) {
    $stmt = $this->mysqli->prepare("
      INSERT INTO $this->table (name, type, value)
      VALUES (?, ?, ?)
      ON DUPLICATE KEY UPDATE value = ?
      ");
    if ($stmt && $stmt->bind_param('ssss', $name, $type, $value, $value) && $stmt->execute())
      return true;
    $this->debug->append("Failed to set $name to $value");
    return false;
  }
}

$monitoring = new Monitoring($debug, $mysqli);
