<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

class TeamsAccounts Extends Base {
  protected $table = 'teams_accounts';

  public function add($team_id, $account_id) {
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table VALUES (?, ?)");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $team_id, $account_id) && $stmt->execute())
      return true;
    return false;
  }

  public function leave($team_id, $account_id) {
    $stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE account_id = ? AND team_id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $account_id, $team_id) && $stmt->execute() && $stmt->affected_rows > 0)
      return true;
    $this->setErrorMessage('Failed to remove you from team');
    return false;
  }
  public function getTeamId($account_id) {
    return $this->getSingle($account_id, 'team_id', 'account_id');
  }
  public function getMemberCount($team_id) {
    $stmt = $this->mysqli->prepare("SELECT COUNT(account_id) FROM $this->table WHERE team_id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $team_id) && $stmt->execute() && $stmt->bind_result($count) && $stmt->fetch())
      return $count;
    return false;
  }
  public function getMembers($team_id) {
    $stmt = $this->mysqli->prepare("
      SELECT
        a.username AS username,
        a.id AS id
      FROM $this->table AS t
      LEFT JOIN " . $this->user->getTableName() . " AS a
      ON t.account_id = a.id
      WHERE t.team_id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $team_id) && $stmt->execute() && $result = $stmt->get_result()) {
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $arr[$row['id']] = $row['username'];
        }
        return $arr;
      } else { return false; }
    }
    $this->debug->append('MySQL query failed: ' . $this->mysqli->error);
    $this->setErrorMessage('Unable to fetch members for team #' . $team_id);
    return false;
  }
}

$teamsaccounts = new TeamsAccounts();
$teamsaccounts->setDebug($debug);
$teamsaccounts->setMysql($mysqli);
$teamsaccounts->setUser($user);
