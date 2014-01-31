<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class News extends Base {
  protected $table = 'news';

  /**
   * Get activation status of post
   * @param id int News ID
   * @return bool true or false
   **/
  public function getActive($id) {
    $this->debug->append("STA " . __METHOD__, 5);
    return $this->getSingle($id, 'active', 'id');
  }

  /**
   * Switch activation status
   * @param id int News ID
   * @return bool true or false
   **/
  public function toggleActive($id) {
    $this->debug->append("STA " . __METHOD__, 5);
    $field = array('name' => 'active', 'type' => 'i', 'value' => !$this->getActive($id));
    return $this->updateSingle($id, $field);
  }

  /**
   * Get all active news
   **/
  public function getAllActive() {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT n.*, a.username AS author FROM $this->table AS n LEFT JOIN " . $this->user->getTableName() . " AS a ON a.id = n.account_id WHERE active = 1 ORDER BY time DESC");
    if ($stmt && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError('E0040');
  }

  /**
   * Get all news
   **/
  public function getAll() {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT n.*, a.username AS author FROM $this->table AS n LEFT JOIN " . $this->user->getTableName() . " AS a ON a.id = n.account_id ORDER BY time DESC");
    if ($stmt && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError('E0039');
  }

  /**
   * Get a specific news entry
   **/
  public function getEntry($id) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("SELECT * FROM $this->table WHERE id = ?");
    if ($stmt && $stmt->bind_param('i', $id) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    return $this->sqlError('E0038');
  }

  /**
   * Update a news entry
   **/
  public function updateNews($id, $header, $content, $active=0) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET content = ?, header = ?, active = ? WHERE id = ?");
    if ($stmt && $stmt->bind_param('ssii', $content, $header, $active, $id) && $stmt->execute() && $stmt->affected_rows == 1)
      return true;
    return $this->sqlError('E0037');
  }

  public function deleteNews($id) {
    $this->debug->append("STA " . __METHOD__, 4);
    if (!is_int($id)) return false;
    $stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $id) && $stmt->execute() && $stmt->affected_rows == 1)
      return true;
    return $this->sqlError('E0036');
  }

  /**
   * Add a new mews entry to the table
   * @param type string Type of the notification
   * @return bool
   **/
  public function addNews($account_id, $aData, $active=false) {
    $this->debug->append("STA " . __METHOD__, 4);
    if (empty($aData['header'])) return false;
    if (empty($aData['content'])) return false;
    if (!is_int($account_id)) return false;
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id, header, content, active) VALUES (?,?,?,?)");
    if ($stmt && $stmt->bind_param('issi', $account_id, $aData['header'], $aData['content'], $active) && $stmt->execute())
      return true;
    return $this->sqlError('E0035');
  }
}

$news = new News();
$news->setDebug($debug);
$news->setMysql($mysqli);
$news->setUser($user);
$news->setErrorCodes($aErrorCodes);
?>
