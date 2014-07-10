<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class CoinAddress extends Base {
  protected $table = 'coin_addresses';
  private $cache = array();

  /**
   * Fetch users coin address for a currency
   * @param userID int UserID
   * @return data string Coin Address
   **/
  public function getCoinAddress($userID, $currency=NULL) {
    if ($currency === NULL) $currency = $this->config['currency'];
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT coin_address
      FROM " . $this->getTableName() . "
      WHERE account_id = ? AND currency = ?
      ");
    if ( $this->checkStmt($stmt) && $stmt->bind_param('is', $userID, $currency) && $stmt->execute() && $result = $stmt->get_result()) {
      if ($result->num_rows == 1) {
        return $result->fetch_object()->coin_address;
      }
    }
    $this->debug->append("Unable to fetch users coin address for " . $currency);
    return $this->sqlError();
  }

  /**
   * Check if a coin address is already set
   * @param address string Coin Address to check for
   * @return bool true or false
   **/
  public function existsCoinAddress($address) {
    $this->debug->append("STA " . __METHOD__, 4);
    return $this->getSingle($address, 'coin_address', 'coin_address', 's') === $address;
  }

  /**
   * Add a new coin address record for a user
   * @param userID int Account ID
   * @param address string Coin Address
   * @param currency string Currency short handle, defaults to config option
   * @return bool true or false
   **/
  public function add($userID, $address, $currency=NULL) {
    if ($currency === NULL) $currency = $this->config['currency'];
    if ($address != $this->getCoinAddress($userID) && $this->existsCoinAddress($address)) {
      $this->setErrorMessage('Unable to update coin address, address already exists');
      return false;
    }
    $stmt = $this->mysqli->prepare("INSERT INTO " . $this->getTableName() . " (account_id, currency, coin_address) VALUES (?, ?, ?)");
    if ( $this->checkStmt($stmt) && $stmt->bind_param('iss', $userID, $currency, $address) && $stmt->execute()) {
      return true;
    }
    return $this->sqlError();
  }

  /**
   * Remove a coin address record for a user
   * @param userID int Account ID
   * @param currency string Currency short handle, defaults to config option
   * @return bool true or false
   **/
  public function remove ($userID, $currency=NULL) {
    if ($currency === NULL) $currency = $this->config['currency'];
    $stmt = $this->mysqli->prepare("DELETE FROM " . $this->getTableName() . " WHERE account_id = ? AND currency = ?");
    if ( $this->checkStmt($stmt) && $stmt->bind_param('is', $userID, $currency) && $stmt->execute()) {
      return true;
    }
    return $this->sqlError();
  }

  /**
   * Update a coin address record for a user and a currency
   * @param userID int Account ID
   * @param address string Coin Address
   * @param currency string Currency short handle, defaults to config option
   * @return bool true or false
   **/
  public function update($userID, $address, $currency=NULL) {
    if ($currency === NULL) $currency = $this->config['currency'];
    if ($address != $this->getCoinAddress($userID) && $this->existsCoinAddress($address)) {
      $this->setErrorMessage('Unable to update coin address, address already exists');
      return false;
    }
    if ($this->getCoinAddress($userID) != NULL) {
      $stmt = $this->mysqli->prepare("UPDATE " . $this->getTableName() . " SET coin_address = ? WHERE account_id = ? AND currency = ?");
      if ( $this->checkStmt($stmt) && $stmt->bind_param('sis', $address, $userID, $currency) && $stmt->execute()) {
        return true;
      }
    } else {
      $stmt = $this->mysqli->prepare("INSERT INTO " . $this->getTableName() . " (coin_address, account_id, currency) VALUES (?, ?, ?)");
      if ( $this->checkStmt($stmt) && $stmt->bind_param('sis', $address, $userID, $currency) && $stmt->execute()) {
        return true;
      }
    }
    return $this->sqlError();
  }
}

$coin_address = new CoinAddress();
$coin_address->setDebug($debug);
$coin_address->setConfig($config);
$coin_address->setMysql($mysqli);
$coin_address->setErrorCodes($aErrorCodes);
