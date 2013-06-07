<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Notification extends Mail {
  var $table = 'notifications';

  /**
   * We check our notification table for existing data
   * so we can avoid duplicate entries
   **/
  public function isNotified($aData) {
    $data = json_encode($aData);
    $stmt = $this->mysqli->prepare("SELECT id FROM $this->table WHERE data = ? LIMIT 1");
    if ($stmt && $stmt->bind_param('s', $data) && $stmt->execute() && $stmt->store_result() && $stmt->num_rows == 1)
      return true;
    // Catchall
    // Does not seem to have a notification set
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
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (type, data) VALUES (?,?)");
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
