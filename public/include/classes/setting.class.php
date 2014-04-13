<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class Setting extends Base {
  protected $table = 'settings';
  private $cache = array();

  /**
   * Fetch all values available and cache them in this class
   * That way we don't fetch them from DB for each call
   */
  public function createCache() {
    if ($aSettings = $this->getAllAssoc()) {
      foreach ($aSettings as $key => $aData) {
        $this->cache[$aData['name']] = $aData['value'];
      }
      return true;
    }
    return false;
  }

  /**
   * Flush our local cache, may be required for upgrades
   * or other places where we need live data
   **/
  public function flushCache() {
    $this->cache = array();
    return true;
  }

  /**
   * Fetch a value from our table
   * @param name string Setting name
   * @return value string Value
   **/
  public function getValue($name, $default="") {
    // Try our class cache first
    if (isset($this->cache[$name])) return $this->cache[$name];
    $stmt = $this->mysqli->prepare("SELECT value FROM $this->table WHERE name = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $name) && $stmt->execute() && $result = $stmt->get_result()) {
      if ($result->num_rows > 0) {
        return $result->fetch_object()->value;
      } else {
        return $default;
      }
    }
    // Log error but return empty string
    $this->sqlError();
    return $default;
  }

  /**
   * Insert or update a setting
   * @param name string Name of the variable
   * @param value string Variable value
   * @return bool
   **/
  public function setValue($name, $value) {
    // Update local cache too
    $this->cache[$name] = $value;
    $stmt = $this->mysqli->prepare("
      INSERT INTO $this->table (name, value)
      VALUES (?, ?)
      ON DUPLICATE KEY UPDATE value = ?");
    if ($stmt && $stmt->bind_param('sss', $name, $value, $value) && $stmt->execute())
      return true;
    return $this->sqlError();
  }
}

$setting = new Setting($debug, $mysqli);
$setting->setDebug($debug);
$setting->setMysql($mysqli);
$setting->setErrorCodes($aErrorCodes);
// Fill our class cache with data so we don't have to run SQL queries all the time
$setting->createCache();
