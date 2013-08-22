<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

// Our base class that defines
// some cross-class functions.
class Base {
  private $sError = '';
  private $values = array(), $types = ''; 

  public function setDebug($debug) {
    $this->debug = $debug;
  }
  public function setMysql($mysqli) {
    $this->mysqli = $mysqli;
  }
  public function setMail($mail) {
    $this->mail = $mail;
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
  public function setToken($token) {
    $this->token = $token;
  }
  public function setBlock($block) {
    $this->block = $block;
  }
  public function setSetting($setting) {
    $this->setting = $setting;
  }
  public function setBitcoin($bitcoin) {
    $this->bitcoin = $bitcoin;
  }
  public function setTokenType($tokentype) {
    $this->tokentype = $tokentype;
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

  /**
   * We may need to generate our bind_param list
   **/
  public function addParam($type, &$value) {
    $this->values[] = $value;
    $this->types .= $type;
  }
  public function getParam() {
    $array = array_merge(array($this->types), $this->values);
    // Clear the data
    $this->values = NULL;
    $this->types = NULL;
    // See here why we need this: http://stackoverflow.com/questions/16120822/mysqli-bind-param-expected-to-be-a-reference-value-given
    if (strnatcmp(phpversion(),'5.3') >= 0) {
      $refs = array();
      foreach($array as $key => $value)
        $refs[$key] = &$array[$key];
      return $refs;
    }
    return $array;
  }
}
?>
