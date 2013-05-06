<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Settings {
  public function __construct($debug, $mysqli, $salt) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->salt = $salt;
    $this->table = 'settings';
  }

  public function getValue($name) {
    $query = $this->mysqli->prepare("SELECT value FROM $this->table WHERE setting=? LIMIT 1");
    $query->bind_param('s', $name);
    $query->execute();
    $query->bind_result($value);
    $query->fetch();
    $query->close();
    return $value;
  }
}

$settings = new Settings($debug, $mysqli, SALT);
