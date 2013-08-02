<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

class Team extends Base {
  protected $table = 'teams';

  public function setOwner($team_id, $owner_id) {
    $field = array('type' => 'i', 'name' => 'owner_id', 'value' => $owner_id);
    return $this->updateSingle($team_id, $field);
  }
  public function getOwnerId($team_id) {
    return $this->getSingle($team_id, 'owner_id', 'id');
  }
  public function isFounder($team_id, $account_id) {
    return $this->getOwnerId($team_id) == $account_id;
  }
  public function getMemberCount($team_id) {
    return $this->teamsaccounts->getMemberCount($team_id);
  }
  public function memberOf($account_id) {
    return $this->teamsaccounts->getTeamId($account_id);
  }
  public function isMember($team_id, $account_id) {
    return $this->teamsaccounts->getTeamId($account_id) == $team_id;
  }
  public function getMembers($team_id) {
    return $this->teamsaccounts->getMembers($team_id);
  }

  public function create($name, $slogan, $owner) {
    $this->debug->append("STA " . __METHOD__, 4);
    if (empty($name)) {
      $this->setErrorMessage('Team name must not be empty');
      return false;
    } else if (preg_match('/[^a-z_\-0-9]/i', $name)) {
      $this->setErrorMessage('Invalid characters, only use alphanumerics');
      return false;
    }
    if (empty($slogan)) {
      $this->setErrorMessage('Team slogan must not be empty');
      return false;
    }
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (name, slogan, owner_id) VALUES (?, ?, ?)");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ssi', $name, $slogan, $owner) && $stmt->execute()) {
      if ($this->join($stmt->insert_id, $owner, true)) {
        return true;
      } else {
        return false;
      }
    }
    $this->setErrorMessage('Team creation failed');
    if ($stmt->sqlstate == '23000') $this->setErrorMessage( 'Team already exists' );
    $this->debug->append('MySQL query failed: ' . $this->mysqli->error);
    return false;
  }

  public function changeOwner($account_id, $team_id, $owner_id) {
    if (! $this->isFounder($team_id, $account_id)) {
      $this->setErrorMessage('You do not seem to be the owner of this team');
      return false;
    }
    if (! $this->isMember($team_id, $owner_id)) {
      $this->setErrorMessage('You can only promote a member of this team to be the new owner');
      return false;
    }
    if ($this->setOwner($team_id, $owner_id))
      return true;
    $this->setErrorMessage('Unknown error');
    return false;
  }

  public function deleteTeam($team_id) {
    $stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $team_id) && $stmt->execute() && $stmt->affected_rows == 1)
      return true;
    return false;
  }

  public function leave($account_id) {
    if (! $team_id = $this->teamsaccounts->getTeamId($account_id)) {
      $this->setErrorMessage('You do not seem to be part of a team');
      return false;
    }
    if ($this->getOwnerId($team_id) == $account_id && $this->getMemberCount($team_id) > 1) {
      $this->setErrorMessage('You are currently the owner of this team. Please promote a new owner first.');
      return false;
    }
    if ($this->getMemberCount($team_id) == 1) {
      if (! $this->deleteTeam($team_id)) {
        $this->setErrorMessage('Failed to remove your now empty team');
        return false;
      }
    }
    if ($this->teamsaccounts->leave($team_id, $account_id))
      return true;
    $this->setErrorMessage($this->teamsaccounts->getError());
    return false;
  }

  public function apply($team_id, $account_id) {
    // Apply to join a team
  }

  public function join($team_id, $account_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    if (!$this->teamsaccounts->add($team_id, $account_id)) {
      $this->setErrorMessage('Failed to join ' . $this->getName($team_id));
      return false;
    }
    return true;
  }
}

$team = new Team();
$team->setDebug($debug);
$team->setConfig($config);
$team->setMysql($mysqli);
$team->setTeamsAccounts($teamsaccounts);
