<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class Worker extends Base {
  protected $table = 'pool_worker';

  /**
   * Update worker list for a user
   * @param account_id int User ID
   * @param data array All workers and their settings
   * @return bool
   **/
  public function updateWorkers($account_id, $data) {
    $this->debug->append("STA " . __METHOD__, 4);
    if (!is_array($data)) {
      $this->setErrorMessage('No workers to update');
      return false;
    }
    $username = $this->user->getUserName($account_id);
    $iFailed = 0;
    foreach ($data as $key => $value) {
    if ('' === $value['username'] || '' === $value['password']) {
      $iFailed++;
    } else {
      // Check worker name first
      if (! preg_match("/^[0-9a-zA-Z_\-]*$/", $value['username'])) {
        $iFailed++;
        continue;
      }
      // Prefix the WebUser to Worker name
      $value['username'] = "$username." . $value['username'];
      $stmt = $this->mysqli->prepare("UPDATE $this->table SET password = ?, username = ?, monitor = ? WHERE account_id = ? AND id = ? LIMIT 1");
      if ( ! ( $this->checkStmt($stmt) && $stmt->bind_param('ssiii', $value['password'], $value['username'], $value['monitor'], $account_id, $key) && $stmt->execute()) )
        $iFailed++;
      }
    }
    if ($iFailed == 0)
      return true;
    return $this->sqlError('E0053', $iFailed);
  }

  /**
   * Fetch all IDLE workers that have monitoring enabled
   * @param none
   * @return data array Workers in IDLE state and monitoring enabled
   **/
  public function getAllIdleWorkers($interval=600) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT w.account_id AS account_id, w.id AS id, w.username AS username
      FROM
      (
        SELECT username AS s_username, MAX(shares_id) AS shares_id, MAX(shares_archive_id) AS shares_archive_id
        FROM
        (
          SELECT
          s.username AS username, MAX(s.id) AS shares_id, NULL AS shares_archive_id
          FROM . " . $this->share->getTableName() . " AS s
          WHERE s.time > DATE_SUB(now(), INTERVAL ? SECOND)
          AND s.our_result = 'Y'
          GROUP BY s.username
          UNION
          SELECT
          sa.username AS username, NULL AS shares_id, MAX(sa.id) AS shares_archive_id
          FROM " . $this->share->getArchiveTableName() . " AS sa
          WHERE sa.time > DATE_SUB(now(), INTERVAL ? SECOND)
          AND sa.our_result = 'Y'
          GROUP BY sa.username
        ) AS derived0
        GROUP BY s_username
      ) AS derived1
      RIGHT JOIN " . $this->getTableName() . " AS w
      ON s_username = w.username
      WHERE w.monitor = 1
      AND shares_id IS NULL
      AND shares_archive_id IS NULL
    ");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $interval, $interval) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError('E0054');
  }

  /**
   * Fetch a specific worker and its status
   * @param id int Worker ID
   * @return mixed array Worker details
   **/
  public function getWorker($id, $interval=600) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT id, username, password, monitor,
        (
          SELECT COUNT(id) FROM " . $this->share->getTableName() . " WHERE our_result = 'Y' AND username = w.username AND time > DATE_SUB(now(), INTERVAL ? SECOND)
        ) + (
          SELECT COUNT(id) FROM " . $this->share->getArchiveTableName() . " WHERE our_result = 'Y' AND username = w.username AND time > DATE_SUB(now(), INTERVAL ? SECOND)
        ) AS count_all,
        (
          SELECT
          IFNULL(SUM(difficulty), 0)
          FROM " . $this->share->getTableName() . "
          WHERE
            username = w.username
            AND our_result = 'Y'
            AND time > DATE_SUB(now(), INTERVAL ? SECOND)
        ) + (
          SELECT
          IFNULL(SUM(difficulty), 0)
          FROM " . $this->share->getArchiveTableName() . "
          WHERE
            username = w.username
            AND our_result = 'Y'
            AND time > DATE_SUB(now(), INTERVAL ? SECOND)
         ) AS shares
       FROM $this->table AS w
       WHERE id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('iiiii', $interval, $interval, $interval, $interval, $id) && $stmt->execute() && $result = $stmt->get_result()) {
      $row = $result->fetch_assoc();
      $row['hashrate'] = round($this->coin->calcHashrate($row['shares'], $interval), 2);
      if ($row['count_all'] > 0) {
        $row['difficulty'] = round($row['shares'] / $row['count_all'], 2);
      } else {
        $row['difficulty'] = 0.00;
      }
      return $row;
    }
    return $this->sqlError('E0055');
  }

  /**
   * Fetch all workers for an account
   * @param account_id int User ID
   * @return mixed array Workers and their settings or false
   **/
  public function getWorkers($account_id, $interval=600) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT id, username, password, monitor,
        (
          SELECT COUNT(id) FROM " . $this->share->getTableName() . " WHERE our_result = 'Y' AND username = w.username AND time > DATE_SUB(now(), INTERVAL ? SECOND)
        ) + (
          SELECT COUNT(id) FROM " . $this->share->getArchiveTableName() . " WHERE our_result = 'Y' AND username = w.username AND time > DATE_SUB(now(), INTERVAL ? SECOND)
        ) AS count_all,
        (
          SELECT
          IFNULL(SUM(difficulty), 0)
          FROM " . $this->share->getTableName() . "
          WHERE
            username = w.username
            AND our_result = 'Y'
            AND time > DATE_SUB(now(), INTERVAL ? SECOND)
        ) + (
          SELECT
          IFNULL(SUM(difficulty), 0)
          FROM " . $this->share->getArchiveTableName() . "
          WHERE
            username = w.username
            AND our_result = 'Y'
            AND time > DATE_SUB(now(), INTERVAL ? SECOND)
        ) AS shares
      FROM $this->table AS w
      WHERE account_id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('iiiii', $interval, $interval, $interval, $interval, $account_id) && $stmt->execute() && $result = $stmt->get_result()) {
      $aData = array();
      while ($row = $result->fetch_assoc()) {
        $row['hashrate'] = round($this->coin->calcHashrate($row['shares'], $interval), 2);
        if ($row['count_all'] > 0) {
          $row['difficulty'] = round($row['shares'] / $row['count_all'], 2);
        } else {
          $row['difficulty'] = 0.00;
        }
        $aData[] = $row;
      }
      return $aData;
    }
    return $this->sqlError('E0056');
  }

  /**
   * Fetch all workers for admin panel
   * @param limit int max amount of workers
   * @return mixed array Workers and their settings or false
   **/
  public function getAllWorkers($iLimit=0, $interval=600, $start=0) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT id, username, password, monitor,
        (
          SELECT COUNT(id) FROM " . $this->share->getTableName() . " WHERE our_result = 'Y' AND username = w.username AND time > DATE_SUB(now(), INTERVAL ? SECOND)
        ) + (
          SELECT COUNT(id) FROM " . $this->share->getArchiveTableName() . " WHERE our_result = 'Y' AND username = w.username AND time > DATE_SUB(now(), INTERVAL ? SECOND)
        ) AS count_all,
        IFNULL(IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty), 0) AS difficulty,
        (
          SELECT
          IFNULL(SUM(difficulty), 0)
          FROM " . $this->share->getTableName() . "
          WHERE
            username = w.username
            AND our_result = 'Y'
            AND time > DATE_SUB(now(), INTERVAL ? SECOND)
        ) + (
          SELECT
          IFNULL(SUM(difficulty), 0)
          FROM " . $this->share->getArchiveTableName() . "
          WHERE
            username = w.username
            AND our_result = 'Y'
            AND time > DATE_SUB(now(), INTERVAL ? SECOND)
        ) AS shares
      FROM $this->table AS w
      ORDER BY shares DESC LIMIT ?,?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('iiiiii', $interval, $interval, $interval, $interval, $start, $iLimit) && $stmt->execute() && $result = $stmt->get_result()) {
      $aData = array();
      while ($row = $result->fetch_assoc()) {
        $row['hashrate'] = round($this->coin->calcHashrate($row['shares'], $interval), 2);
        if ($row['count_all'] > 0) {
          $row['avg_difficulty'] = round($row['shares'] / $row['count_all'], 2);
        } else {
          $row['avg_difficulty'] = 0.00;
        }
        $aData[] = $row;
      }
      return $aData;
    }
    return $this->sqlError('E0057');
  }

  /**
   * Get all currently active workers in the past 2 minutes
   * @param none
   * @return data mixed int count if any workers are active, false otherwise
   **/
  public function getCountAllActiveWorkers($interval=120) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT COUNT(DISTINCT(username)) AS total
      FROM "  . $this->share->getTableName() . "
      WHERE our_result = 'Y'
      AND time > DATE_SUB(now(), INTERVAL ? SECOND)");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $interval) && $stmt->execute() && $result = $stmt->get_result())
      return $this->memcache->setCache(__FUNCTION__, $result->fetch_object()->total);
    return $this->sqlError();
  }

  /**
   * Add new worker to an existing web account
   * The webuser name is prefixed to the worker name
   * Passwords are plain text for pushpoold
   * @param account_id int User ID
   * @param workerName string Worker name
   * @param workerPassword string Worker password
   * @return bool
   **/
  public function addWorker($account_id, $workerName, $workerPassword) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ('' === $workerName || '' === $workerPassword) {
      $this->setErrorMessage($this->getErrorMsg('E0058'));
      return false;
    }
    if (!preg_match("/^[0-9a-zA-Z_\-]*$/", $workerName)) {
      $this->setErrorMessage($this->getErrorMsg('E0072'));
      return false;
    }
    $username = $this->user->getUserName($account_id);
    $workerName = "$username.$workerName";
    if (strlen($workerName) > 50) {
      $this->setErrorMessage($this->getErrorMsg('E0073'));
      return false;
    }
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id, username, password) VALUES(?, ?, ?)");
    if ($this->checkStmt($stmt) && $stmt->bind_param('iss', $account_id, $workerName, $workerPassword)) {
      if (!$stmt->execute()) {
        if ($stmt->sqlstate == '23000') return $this->sqlError('E0059');
      } else {
        return true;
      }
    }
    return $this->sqlError('E0060');
  }

  /**
   * Delete existing worker from account
   * @param account_id int User ID
   * @param id int Worker ID
   * @return bool
   **/
  public function deleteWorker($account_id, $id) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE account_id = ? AND id = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $account_id, $id) && $stmt->execute() && $stmt->affected_rows == 1)
        return true;
    return $this->sqlError('E0061');
  }
}

$worker = new Worker();
$worker->setDebug($debug);
$worker->setMysql($mysqli);
$worker->setMemcache($memcache);
$worker->setShare($share);
$worker->setConfig($config);
$worker->setUser($user);
$worker->setErrorCodes($aErrorCodes);
$worker->setCoin($coin);
?>
