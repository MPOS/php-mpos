<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Notification extends Mail {
  var $table = 'notifications';
  var $tableSettings = 'notification_settings';

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
  private function updateSingle($id, $field, $table='') {
    if (empty($table)) $table = $this->table;
    $this->debug->append("STA " . __METHOD__, 4); 
    $stmt = $this->mysqli->prepare("UPDATE $table SET " . $field['name'] . " = ? WHERE id = ? LIMIT 1");
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
    $this->debug->append("STA " . __METHOD__, 4);
    $data = json_encode($aData);
    $stmt = $this->mysqli->prepare("SELECT id FROM $this->table WHERE data = ? AND active = 1 LIMIT 1");
    if ($stmt && $stmt->bind_param('s', $data) && $stmt->execute() && $stmt->store_result() && $stmt->num_rows == 1)
      return true;
    // Catchall
    // Does not seem to have a notification set
    $this->setErrorMessage("Unable to run query: " . $this->mysqli->error);
    return false;
  }

  /**
   * Get all active notifications
   **/
  public function getAllActive() {
    $this->debug->append("STA " . __METHOD__, 4);
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
  public function addNotification($account_id, $type, $data) {
    $this->debug->append("STA " . __METHOD__, 4);
    // Store notification data as json
    $data = json_encode($data);
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id, type, data, active) VALUES (?, ?,?,1)");
    if ($stmt && $stmt->bind_param('iss', $account_id, $type, $data) && $stmt->execute())
      return true;
    $this->debug->append("Failed to add notification for $type with $data: " . $this->mysqli->error);
    $this->setErrorMessage("Unable to add new notification");
    return false;
  }

  /**
   * Fetch notifications for a user account
   * @param id int Account ID
   * @return array Notification data
   **/
  public function getNofifications($account_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE account_id = ? ORDER BY time DESC");
    if ($stmt && $stmt->bind_param('i', $account_id) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    // Catchall
    return false;
  }

  /**
   * Fetch notification settings for user account
   * @param id int Account ID
   * @return array Notification settings
   **/
  public function getNotificationSettings($account_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->tableSettings WHERE account_id = ?");
    if ($stmt && $stmt->bind_param('i', $account_id) && $stmt->execute() && $result = $stmt->get_result()) {
      while ($row = $result->fetch_assoc()) {
        $aData[$row['type']] = $row['active'];
      }
      return $aData;
    }
    // Catchall
    return false;
  }

  /**
   * Update accounts notification settings
   * @param account_id int Account ID
   * @param data array Data array
   * @return bool
   **/
  public function updateSettings($account_id, $data) {
    $this->debug->append("STA " . __METHOD__, 4);
    $failed = $ok = 0;
    foreach ($data as $type => $active) {
      // Does an entry exist already
      $stmt = $this->mysqli->prepare("SELECT * FROM $this->tableSettings WHERE account_id = ? AND type = ?");
      if ($stmt && $stmt->bind_param('is', $account_id, $type) && $stmt->execute() && $stmt->store_result() && $stmt->num_rows() > 0) {
        // We found a matching row
        $stmt = $this->mysqli->prepare("UPDATE $this->tableSettings SET active = ? WHERE type = ? AND account_id = ?");
        if ($stmt && $stmt->bind_param('isi', $active, $type, $account_id) && $stmt->execute() && $stmt->close()) {
          $ok++;
        } else {
          $failed++;
        }
      } else {
        $stmt = $this->mysqli->prepare("INSERT INTO $this->tableSettings (active, type, account_id) VALUES (?,?,?)");
        if ($stmt && $stmt->bind_param('isi', $active, $type, $account_id) && $stmt->execute()) {
          $ok++;
        } else {
          $failed++;
        }
      }
    }
    if ($failed > 0) {
      $this->setErrorMessage('Failed to update ' . $failed . ' settings');
      return false;
    }
    return true;
  }
}

$notification = new Notification();
$notification->setDebug($debug);
$notification->setMysql($mysqli);
$notification->setSmarty($smarty);
$notification->setConfig($config);
