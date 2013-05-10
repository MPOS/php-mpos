<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Share {
  private $sError = '';
  private $table = 'shares';
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

  public function getSharesForAccountsByTimeframe($current='', $old='') {
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
                                      WHERE
                                        UNIX_TIMESTAMP(time) BETWEEN ? AND ?
                                      AND
                                        our_result = 'Y'
                                      GROUP BY account
                                    ) validT
                                    LEFT JOIN
                                    (
                                      SELECT DISTINCT
                                        SUBSTRING_INDEX( `username` , '.', 1 ) as account,
                                        COUNT(id) AS invalid
                                      FROM $this->table
                                      WHERE
                                        UNIX_TIMESTAMP(time) BETWEEN ? AND ?
                                      AND
                                        our_result = 'N'
                                      GROUP BY account
                                    ) invalidT
                                    ON validT.account = invalidT.account
                                    INNER JOIN accounts a ON a.username = validT.account
                                    GROUP BY a.username DESC");
    echo $this->mysqli->error;
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('iiii', $old, $current, $old, $current);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_all(MYSQLI_ASSOC);
    }
    return false;
  }

  public function getFinderByTimeframe($current='', $old='') {
    $stmt = $this->mysqli->prepare("SELECT
                                      SUBSTRING_INDEX( `username` , '.', 1 ) AS account
                                    FROM $this->table
                                    WHERE upstream_result = 'Y'
                                      AND UNIX_TIMESTAMP(time) BETWEEN ? AND ?
                                    ORDER BY id DESC");
    echo $this->mysqli->error;
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('ii', $old, $current);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_object()->account;
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
