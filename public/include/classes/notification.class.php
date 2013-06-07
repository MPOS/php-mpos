<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Notification extends Mail {
  var $table = 'notifications';

  public function setInactive($id) {
    $field = array(
      'name' => 'active',
      'type' => 'i',
      'value' => 0
    );
    return $this->updateSingle($id, $field);
  }

  /** 
   * Update a single row in a table
   * @param userID int Account ID
   * @param field string Field to update
   * @return bool
   **/
  private function updateSingle($id, $field) {
    $this->debug->append("STA " . __METHOD__, 4); 
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET " . $field['name'] . " = ? WHERE id = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param($field['type'].'i', $field['value'], $id) && $stmt->execute())
      return true;
    $this->debug->append("Unable to update " . $field['name'] . " with " . $field['value'] . " for ID $id");
    return false;
  }
  /**
   * We check our notification table for existing data
   * so we can avoid duplicate entries
   **/
  public function isNotified($aData) {
    $data = json_encode($aData);
    $stmt = $this->mysqli->prepare("SELECT id FROM $this->table WHERE data = ? AND active = 1 LIMIT 1");
    if ($stmt && $stmt->bind_param('s', $data) && $stmt->execute() && $stmt->store_result() && $stmt->num_rows == 1)
      return true;
    // Catchall
    // Does not seem to have a notification set
    return false;
  }

  /**
   * Get all active notifications
   **/
  public function getAllActive() {
    $stmt =$this->mysqli->prepare("SELECT id, data FROM $this->table WHERE active = 1 LIMIT 1");
    if ($stmt && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    // Catchall
    return false;
  }

  /**
   * Add a new notification to the table
   * @param type string Type of the notification
   * @return bool
   **/
  public function addNotification($type, $data) {
    // Store notification data as json
    $data = json_encode($data);
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (type, data, active) VALUES (?,?,1)");
    if ($stmt && $stmt->bind_param('ss', $type, $data) && $stmt->execute())
      return true;
    $this->debug->append("Failed to add notification for $type with $data: " . $this->mysqli->error);
    $this->setErrorMessage("Unable to add new notification");
    return false;
  }
}

$notification = new Notification();
$notification->setDebug($debug);
$notification->setMysql($mysqli);
$notification->setSmarty($smarty);
$notification->setConfig($config);
