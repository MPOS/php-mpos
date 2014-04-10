<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

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
   * We check our notification table for existing data
   * so we can avoid duplicate entries
   **/
  public function isNotified($aData) {
    $this->debug->append("STA " . __METHOD__, 4);
    $data = json_encode($aData);
    $stmt = $this->mysqli->prepare("SELECT id FROM $this->table WHERE data = ? AND active = 1 LIMIT 1");
    if ($stmt && $stmt->bind_param('s', $data) && $stmt->execute() && $stmt->store_result() && $stmt->num_rows == 1)
      return true;
    return $this->sqlError('E0041');
  }

  /**
   * Get all active notifications
   **/
  public function getAllActive($strType) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt =$this->mysqli->prepare("SELECT id, data FROM $this->table WHERE active = 1 AND type = ?");
    if ($stmt && $stmt->bind_param('s', $strType) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError('E0042');
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
    return $this->sqlError('E0043');
  }

  /**
   * Fetch notifications for a user account
   * @param id int Account ID
   * @return array Notification data
   **/
  public function getNotifications($account_id,$limit=50) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE account_id = ? ORDER BY time DESC LIMIT ?");
    if ($stmt && $stmt->bind_param('ii', $account_id, $limit) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->getError();
  }

  /**
   * Fetch notification settings for user account
   * @param id int Account ID
   * @return array Notification settings
   **/
  public function getNotificationSettings($account_id) {
    // Some defaults, we cover them here so we can avoid adding default settings on user creation
    $aDefaults = array( 'newsletter' => 1 );
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->tableSettings WHERE account_id = ?");
    if ($stmt && $stmt->bind_param('i', $account_id) && $stmt->execute() && $result = $stmt->get_result()) {
      if ($result->num_rows > 0) {
        $aFound = array();
        while ($row = $result->fetch_assoc()) {
          if (array_key_exists($row['type'], $aDefaults)) $aFound[] = $row['type'];
          $aData[$row['type']] = $row['active'];
        }
        // Check found types against our defaults, set if required
        foreach ($aDefaults as $type => $value) {
          if (!in_array($type, $aFound)) {
            $aData[$type] = $value;
          }
        }
        return $aData;
      } else {
        foreach ($aDefaults as $type => $value) $aData[$type] = $value;
        return $aData;
      }
    }
    return $this->sqlError('E0045');
  }

  /**
   * Get all accounts that wish to receive a specific notification
   * @param strType string Notification type
   * @return data array User Accounts
   **/
  public function getNotificationAccountIdByType($strType) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT account_id FROM $this->tableSettings WHERE type = ? AND active = 1");
    if ($stmt && $stmt->bind_param('s', $strType) && $stmt->execute() && $result = $stmt->get_result()) {
      return $result->fetch_all(MYSQLI_ASSOC);
    }
    return $this->sqlError('E0046');
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
      $stmt = $this->mysqli->prepare("INSERT INTO $this->tableSettings (active, type, account_id) VALUES (?,?,?) ON DUPLICATE KEY UPDATE active = ?");
      if ($stmt && $stmt->bind_param('isii', $active, $type, $account_id, $active) && $stmt->execute()) {
        $ok++;
      } else {
        $failed++;
      }
    }
    if ($failed > 0) {
      $this->setErrorMessage($this->getErrorMsg('E0047', $failed));
      return $this->sqlError();
    }
    $this->log->log("info", "User $account_id updated notification settings");
    return true;
  }

  /**
   * Send a specific notification setup in notification_settings
   * @param type string Notification type
   * @return bool
   **/
  public function sendNotification($account_id, $strType, $aMailData) {
    // Check if we notified for this event already
    if ( $this->isNotified($aMailData) ) {
      $this->setErrorMessage('A notification for this event has been sent already');
      return false;
    }
    // Check if this user wants strType notifications
    $stmt = $this->mysqli->prepare("SELECT account_id FROM $this->tableSettings WHERE type = ? AND active = 1 AND account_id = ?");
    if ($stmt && $stmt->bind_param('si', $strType, $account_id) && $stmt->execute() && $stmt->bind_result($id) && $stmt->fetch()) {
      if ($stmt->close() && $this->sendMail('notifications/' . $strType, $aMailData) && $this->addNotification($account_id, $strType, $aMailData)) {
        return true;
      } else {
        $this->setErrorMessage('SendMail call failed: ' . $this->getError());
        return false;
      }
    } else {
      $this->setErrorMessage('User disabled ' . $strType . ' notifications');
      return true;
    }
    $this->setErrorMessage('Error sending mail notification');
    return false;
  }

  /**
   * Cleanup old notifications
   * @param none
   * @return bool true or false
   **/
  public function cleanupNotifications($days=7) {
    $failed = 0;
    $this->deleted = 0;
    $stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE time < (NOW() - ? * 24 * 60 * 60)");
    if (! ($this->checkStmt($stmt) && $stmt->bind_param('i', $days) && $stmt->execute())) {
      $failed++;
    } else {
      $this->deleted += $stmt->affected_rows;
    }
    if ($failed > 0) {
      $this->setCronMessage('Failed to delete ' . $failed . ' notifications from ' . $this->table . ' table');
      return false;
    }
    return true;
  }
}

$notification = new Notification();
$notification->setDebug($debug);
$notification->setLog($log);
$notification->setMysql($mysqli);
$notification->setSmarty($smarty);
$notification->setConfig($config);
$notification->setSetting($setting);
$notification->setErrorCodes($aErrorCodes);
?>
