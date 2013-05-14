<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Share {
  private $sError = '';
  private $table = 'shares';
  private $oUpstream;
  private $iLastUpstreamId;
  // This defines each share
  public $rem_host, $username, $our_result, $upstream_result, $reason, $solution, $time;

  public function __construct($debug, $mysqli, $salt) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->debug->append("Instantiated Share class", 2);
  }

  // get and set methods
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }

  public function getTableName() {
    return $this->table;
  }

  public function getRoundShares() {
    $stmt = $this->mysqli->prepare("
      SELECT
        ( SELECT IFNULL(count(id), 0)
        FROM $this->table
        WHERE UNIX_TIMESTAMP(time) >IFNULL((SELECT MAX(time) FROM blocks),0)
        AND our_result = 'Y' ) as valid,
        ( SELECT IFNULL(count(id), 0)
      FROM $this->table
      WHERE UNIX_TIMESTAMP(time) >IFNULL((SELECT MAX(time) FROM blocks),0)
      AND our_result = 'N' ) as invalid
    ");
    echo $this->mysqli->error;
    if ($this->checkStmt($stmt)) {

      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_assoc();
    }
    return false;
  }

  public function getSharesForAccounts($previous_upstream=0, $current_upstream) {
    $stmt = $this->mysqli->prepare("SELECT
      a.id,
      validT.account AS username,
      sum(validT.valid) as valid,
      IFNULL(sum(invalidT.invalid),0) as invalid
      FROM
      (
        SELECT DISTINCT
        SUBSTRING_INDEX( `username` , '.', 1 ) as account,
          COUNT(id) AS valid
          FROM $this->table
          WHERE id BETWEEN ? AND ?
          AND our_result = 'Y'
          GROUP BY account
        ) validT
        LEFT JOIN
        (
          SELECT DISTINCT
          SUBSTRING_INDEX( `username` , '.', 1 ) as account,
            COUNT(id) AS invalid
            FROM $this->table
            WHERE id BETWEEN ? AND ?
            AND our_result = 'N'
            GROUP BY account
          ) invalidT
          ON validT.account = invalidT.account
          INNER JOIN accounts a ON a.username = validT.account
          GROUP BY a.username DESC");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('iiii', $previous_upstream, $current_upstream, $previous_upstream, $current_upstream);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_all(MYSQLI_ASSOC);
    }
    return false;
  }

  public function moveArchive($previous_upstream=0, $current_upstream,$block_id) {
    $archive_stmt = $this->mysqli->prepare("INSERT INTO shares_archive (share_id, username, our_result, upstream_result, block_id, time)
      SELECT id, username, our_result, upstream_result, ?, time
      FROM $this->table
      WHERE id BETWEEN ? AND ?");
    $delete_stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE id BETWEEN ? AND ?");
    if ($this->checkStmt($archive_stmt) && $this->checkStmt($delete_stmt)) {
      $archive_stmt->bind_param('iii', $block_id, $previous_upstream, $current_upstream);
      $archive_stmt->execute();
      $delete_stmt->bind_param('ii', $previous_upstream, $current_upstream);
      $delete_stmt->execute();
      $delete_stmt->close();
      $archive_stmt->close();
      return true;
    }
    return false;
  }

  public function setLastUpstreamId() {
    $this->iLastUpstreamId = @$this->oUpstream->id ? $this->oUpstream->id : 0;
  }
  public function getLastUpstreamId() {
    return @$this->iLastUpstreamId;
  }
  public function getUpstreamFinder() {
    return @$this->oUpstream->account;
  }
  public function getUpstreamId() {
    return @$this->oUpstream->id;
  }
  public function setUpstream($time='') {
    $stmt = $this->mysqli->prepare("SELECT
      SUBSTRING_INDEX( `username` , '.', 1 ) AS account, id
      FROM $this->table
      WHERE upstream_result = 'Y'
      AND UNIX_TIMESTAMP(time) BETWEEN ? AND (? + 1)
      ORDER BY id ASC LIMIT 1");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('ii', $time, $time);
      $stmt->execute();
      if (! $result = $stmt->get_result()) {
        $this->setErrorMessage("No result returned from query");
        $stmt->close();
      }
      $stmt->close();
      $this->oUpstream = $result->fetch_object();
      return true;
    }
    return false;
  }

  private function checkStmt($bState) {
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Internal application Error');
      return false;
    }
    return true;
  }
}

$share = new Share($debug, $mysqli, SALT);
