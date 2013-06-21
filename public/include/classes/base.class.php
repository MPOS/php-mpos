<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

// Our base class that defines
// some cross-class functions.
class Base {
  private $sError = '';

  public function setDebug($debug) {
    $this->debug = $debug;
  }
  public function setMysql($mysqli) {
    $this->mysqli = $mysqli;
  }
  public function setSmarty($smarty) {
    $this->smarty = $smarty;
  }
  public function setUser($user) {
    $this->user = $user;
  }
  public function setConfig($config) {
    $this->config = $config;
  }
  public function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }

  /**
   * Get a single row from the table
   * @param value string Value to search for
   * @param search Return column to search for
   * @param field string Search column
   * @param type string Type of value
   * @return array Return result
   **/
  protected function getSingle($value, $search='id', $field='id', $type="i") {
    $this->debug->append("STA " . __METHOD__, 4); 
    $stmt = $this->mysqli->prepare("SELECT $search FROM $this->table WHERE $field = ? LIMIT 1");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param($type, $value);
      $stmt->execute();
      $stmt->bind_result($retval);
      $stmt->fetch();
      $stmt->close();
      return $retval;
    }
    return false;
  }

  function checkStmt($bState) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Internal application Error');
      return false;
    }
    return true;
  }
  /**
   * Update a single row in a table
   * @param userID int Account ID
   * @param field string Field to update
   * @return bool
   **/
  protected function updateSingle($id, $field, $table='') {
    if (empty($table)) $table = $this->table;
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("UPDATE $table SET " . $field['name'] . " = ? WHERE id = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param($field['type'].'i', $field['value'], $id) && $stmt->execute())
      return true;
    $this->debug->append("Unable to update " . $field['name'] . " with " . $field['value'] . " for ID $id");
    return false;
  }
}
?>
