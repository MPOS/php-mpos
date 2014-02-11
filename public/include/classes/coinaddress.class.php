<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

class CoinAddress extends Base {
  var $table = 'coin_addresses';

  public function setCurrency($currency) {
    $this->currency = $currency;
  }
  /**
   * Check whether the coin address already exists
   * @param address string Coin Address
   * @return bool address exists?
   **/
  public function existsCoinAddress($address) {
    $this->debug->append("STA " . __METHOD__, 4);
    return $this->getSingle($address, 'address', 'address') === $address;
  }

  /**
   * Fetch coin address for one account
   * @param account_id int Account ID
   * @return string address on success, bool on failure
   **/
  public function getCoinAddress($account_id) {
    $this->debug->append("STA " . __METHOD__, 4);

    $stmt = $this->mysqli->prepare("SELECT address FROM $this->table WHERE account_id=? AND coin=?");
    if ($stmt && $stmt->bind_param('is', $account_id, $this->currency) && $stmt->execute() && $stmt->bind_result($coinAddress) && $stmt->fetch())
      return $coinAddress; 
    return false;
  }

  /**
   * Insert or update coin address
   * @param account_id int Account ID
   * @param coin_address string Coin Address
   * @return bool insert or update success
   **/
  public function insertOrUpdateCoinAddress($account_id, $coin_address, $threshold) {
    $this->debug->append("STA " . __METHOD__, 4);

    if ($old_address = $this->getCoinAddress($account_id)) {
      return $this->updateCoinAddress($account_id, $old_address, $coin_address, $threshold);
    } else {
      return $this->insertCoinAddress($account_id, $coin_address, $threshold);
    }
  }

  /**
   * update coin address
   * @param account_id int Account ID
   * @param old_address string Address to update
   * @param coin_address string Coin Address
   * @return bool update success
   **/
  public function updateCoinAddress($account_id, $old_address, $coin_address, $threshold) {
    $this->debug->append("STA " . __METHOD__, 4);
    $str = "UPDATE $this->table SET address = ?, ap_threshold = ? WHERE address = ?";
    $stmt = $this->mysqli->prepare($str);

    return $this->checkStmt($stmt) && $stmt->bind_param('sds', $coin_address, $threshold, $old_address) && $stmt->execute();
  }

  /**
   * insert coin address
   * @param account_id int Account ID
   * @param coin_address string Coin Address
   * @return bool insert success
   **/
  public function insertCoinAddress($account_id, $coin_address, $threshold) {
    $this->debug->append("STA " . __METHOD__, 4);
    $this->debug->append("Currency in coinaddress is: ". $this->currency);
    $str = "INSERT INTO $this->table (address, coin, account_id, ap_threshold) VALUES (?,?,?,?)";
    $stmt = $this->mysqli->prepare($str);

    return $this->checkStmt($stmt) && $stmt->bind_param('ssid', $coin_address, $this->currency, $account_id, $threshold) && $stmt->execute();
  }
}

$coinAddress = new CoinAddress();
$coinAddress->setDebug($debug);
$coinAddress->setMysql($mysqli);
$coinAddress->setErrorCodes($aErrorCodes);
$coinAddress->setCurrency($currency);
?>
