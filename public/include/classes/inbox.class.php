<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

class Inbox extends Base {
  protected $table = 'inbox';

  /**
   * Fetch all inbox messages for an account
   * @param account_id int User ID
   * @param $start int Fetch rows starting from this offset
   * @param limit int Only display this many transactions
   * @return data array Database fields as defined in SELECT
   */
  public function getAllMessages($account_id, $start=0, $limit=30) {
    $this->debug->append("STA " . __METHOD__, 4);
    $sql = "
      SELECT
        SQL_CALC_FOUND_ROWS
        t.id AS id,
        t.account_id_to AS account_id_to,
        t.account_id_from AS account_id_from,
        a.username AS username,
        t.subject AS subject,
        t.content AS content,
        t.time AS time,
        t.is_read AS is_read
      FROM $this->table AS t
      LEFT JOIN " . $this->user->getTableName() . " AS a ON t.account_id_from = a.id
      WHERE ( account_id_to = ? )
      ORDER BY id DESC
      LIMIT ?,?";
    $this->addParam('i', $account_id);
    $this->addParam('i', $start);
    $this->addParam('i', $limit);
    $stmt = $this->mysqli->prepare($sql);
    if ($this->checkStmt($stmt) && call_user_func_array( array($stmt, 'bind_param'), $this->getParam()) && $stmt->execute() && $result = $stmt->get_result()) {
      // Fetch matching row count
      $num_rows = $this->mysqli->prepare("SELECT FOUND_ROWS() AS num_rows");
      if ($num_rows->execute() && $row_count = $num_rows->get_result()->fetch_object()->num_rows)
        $this->num_rows = $row_count;

      $rows = $result->fetch_all(MYSQLI_ASSOC);
      $stmt = $this->mysqli->prepare("UPDATE $this->table SET is_read = 1 WHERE id = ? LIMIT 1");
      foreach($rows as $row) {
        if ($row["is_read"] == 0) {
          $stmt->bind_param('i', $row['id']);
          $stmt->execute();
        }
      }
      $stmt->close();
      return $rows;
    }

    return $this->setErrorMessage($this->mysqli->error);
  }

  /**
   * Fetch an inbox message
   * @param $message_id int The message ID
   * @param account_id int ID of user that received the message
   * @return data array Database fields as defined in SELECT
   */
  public function getMessage($message_id, $account_id = 0) {
    $this->debug->append("STA " . __METHOD__, 4);
    $sql = "
      SELECT
        t.id AS id,
        t.account_id_to AS account_id_to,
        t.account_id_from AS account_id_from,
        a.username AS username,
        t.subject AS subject,
        t.content AS content,
        t.time AS time,
        t.is_read AS is_read
      FROM $this->table AS t
      LEFT JOIN " . $this->user->getTableName() . " AS a ON t.account_id_from = a.id
      WHERE ( t.id = ? )";
    $this->addParam('i', $message_id);
    if ($account_id != 0) {
      $sql .= " AND ( t.account_id_to = ? )";
      $this->addParam('i', $account_id);
    }
    $sql .= "LIMIT 1";
    $stmt = $this->mysqli->prepare($sql);

    if ($this->checkStmt($stmt) && call_user_func_array( array($stmt, 'bind_param'), $this->getParam()) && $stmt->execute() && $result = $stmt->get_result()) {
      return $result->fetch_array(MYSQLI_ASSOC);
    }
    return $this->sqlError();
  }

  /**
   * Returns the number of unread messages a user has
   * @param $account_id int The ID of the user
   * @return array The number of unread messages
   */
  public function getUnreadCount($account_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
        COUNT(*) AS c
      FROM $this->table AS t
      WHERE ( t.account_id_to = ? )
      AND ( t.is_read = 0 )
      LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $account_id) && $stmt->execute() && $result = $stmt->get_result()) {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      return $row['c'];
    }
    return $this->sqlError('E0069');
  }

  /**
   * Add a new message entry to the table
   * @param $account_id int The ID of the member sending the message, or 0 to broadcast the message
   * @param $aData array The message data
   */
  public function addMessage($account_id, $aData) {
    $this->debug->append("STA " . __METHOD__, 4);
    if (!is_array($aData)) return false;
    if (empty($aData['account_id_to']) && $aData['account_id_to'] !== 0 && $aData['account_id_to'] !== "0") return false;
    if (empty($aData['subject']) || trim($aData['subject']) == '') {
      $this->setErrorMessage($this->getErrorMsg('E0067'));
      return false;
    }
    if (empty($aData['content']) || trim($aData['content']) == '') {
      $this->setErrorMessage($this->getErrorMsg('E0068'));
      return false;
    }

    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id_to, account_id_from, subject, content) VALUES (?,?,?,?)");
    if ($aData['account_id_to'] === 0 || $aData['account_id_to'] === "0") {
      $users = $this->user->getUsers();
      foreach($users as $user) {
        if ($user['id'] != $account_id) {
          $stmt->bind_param('iiss', $user['id'], $account_id, $aData['subject'], $aData['content']);
          if ($this->checkStmt($stmt) && !$stmt->execute()) {
            $stmt->close();
            return $this->sqlError('E0069');
          }
        }
      }

      $stmt->close();
      return true;
    } else {
      if ($this->checkStmt($stmt) && $stmt->bind_param('iiss', $aData['account_id_to'], $account_id, $aData['subject'], $aData['content']) && $stmt->execute()) {
        $stmt->close();
        return true;
      }
    }

    return $this->sqlError('E0069');
  }

  /**
   * Sends a notice to the user inbox
   * @param $account_id int The ID of the user getting the notice
   * @param $template string The template file
   * @param $aData array The template data
   * @return bool
   */
  public function addNotice($account_id, $template, $aData) {
    $this->debug->append("STA " . __METHOD__, 4);
    $this->smarty->clearCache(BASEPATH . 'templates/mail/' . $template  . '_inbox.tpl');

    $this->smarty->assign('DATA', $aData);
    $content = $this->smarty->fetch(BASEPATH . 'templates/mail/' . $template  . '_inbox.tpl');
    $aData = array(
      'account_id_to' => $account_id,
      'account_id_from' => 0,
      'subject' => 'NOTICE: ' . $aData['subject'],
      'content' => $content
    );
    return $this->addMessage(0, $aData);
  }

  /**
   * Add a new message entry to the table
   * @param $account_id int The ID of the member writing the reply
   * @param $aData array The message data
   * @return bool
   **/
  public function addReply($account_id, $aData) {
    $this->debug->append("STA " . __METHOD__, 4);
    if (!is_array($aData)) return false;
    if (empty($aData['message_id'])) return false;
    if (empty($aData['subject']) || trim($aData['subject']) == '') {
      $this->setErrorMessage($this->getErrorMsg('E0067'));
      return false;
    }
    if (empty($aData['content']) || trim($aData['content']) == '') {
      $this->setErrorMessage($this->getErrorMsg('E0068'));
      return false;
    }

    $message = $this->getMessage($aData['message_id'], $account_id);
    if (empty($message)) {
      $this->setErrorMessage($this->getErrorMsg('E0066'));
      return false;
    }

    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id_to, account_id_from, subject, content) VALUES (?,?,?,?)");
    if ($this->checkStmt($stmt) && $stmt->bind_param('iiss', $message['account_id_from'], $account_id, $aData['subject'], $aData['content']) && $stmt->execute())
      return true;
    return $this->sqlError('E0069');
  }

  /**
   * Sends a site message from the contact form
   * @param $account_id int The ID of the user sending the message
   * @param $subject string The subject of the message
   * @param $message string The message
   * @return bool true or false
   */
  public function contactForm($account_id, $subject, $message) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id_to, account_id_from, subject, content) VALUES(?,?,?,?)");
    if ($this->checkStmt($stmt)) {
      $admins = $this->user->getAllAdmins();
      foreach($admins as $admin) {
        $stmt->bind_param('iiss', $admin['id'], $account_id, $subject, $message);
        if (!$stmt->execute()) {
          return $this->sqlError('E0069');
        }
      }
      $stmt->close();
      return true;
    }

    $this->setErrorMessage($this->getErrorMsg('E0069'));
    return false;
  }

  /**
   * Delete a message from the table
   * @param $message_id int The ID of the message to delete
   * @param int $account_id The ID of the user deleting the message
   * @return bool true or false
   */
  public function deleteMessage($message_id, $account_id = 0) {
    $this->debug->append("STA " . __METHOD__, 4);
    if (!is_int($message_id)) return false;
    if ($account_id != 0) {
      $message = $this->getMessage($message_id, $account_id);
      if (empty($message)) {
        $this->setErrorMessage($this->getErrorMsg('E0066'));
        return false;
      }
    }

    $stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE id = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $message_id) && $stmt->execute() && $stmt->affected_rows == 1)
      return true;
    return $this->sqlError('E0070');
  }
}

$inbox = new Inbox();
$inbox->setDebug($debug);
$inbox->setMysql($mysqli);
$inbox->setConfig($config);
$inbox->setUser($user);
$inbox->setSmarty($smarty);
$inbox->setErrorCodes($aErrorCodes);
?>