<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

/**
 * We use a wrapper class around BitcoinClient to add
 * some basic caching functionality and some debugging
 **/
class BitcoinWrapper extends BitcoinClient {
  public function __construct($type, $username, $password, $host, $debug_level, $debug_object, $memcache) {
    $this->type = $type;
    $this->username = $username;
    $this->password = $password;
    $this->host = $host;
    // $this->debug is already used
    $this->oDebug = $debug_object;
    $this->memcache = $memcache;
    $debug_level > 0 ? $debug_level = true : $debug_level = false;
    return parent::__construct($this->type, $this->username, $this->password, $this->host, '', $debug_level);
  }
  /**
   * Wrap variouns methods to add caching
   **/
  // Caching this, used for each can_connect call
  public function getinfo() {
    $this->oDebug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    return $this->memcache->setCache(__FUNCTION__, parent::getinfo(), 30);
  }
  public function getmininginfo() {
    $this->oDebug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    return $this->memcache->setCache(__FUNCTION__, parent::getmininginfo(), 30);
  }
  // Wrapper to check our wallet balance from the DEFAULT account only
  public function getbalance() {
    $this->oDebug->append("STA " . __METHOD__, 4);
    $aAccounts = parent::listaccounts();
    return $aAccounts[''];
  }
  public function getblockcount() {
    $this->oDebug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    return $this->memcache->setCache(__FUNCTION__, parent::getblockcount(), 30);
  }
  public function getdifficulty() {
    $this->oDebug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    $data = parent::getdifficulty();
    // Check for PoS/PoW coins
    if (is_array($data) && array_key_exists('proof-of-work', $data))
      $data = $data['proof-of-work'];
    return $this->memcache->setCache(__FUNCTION__, $data, 30);
  }
  public function getestimatedtime($iCurrentPoolHashrate) {
    $this->oDebug->append("STA " . __METHOD__, 4);
    if ($iCurrentPoolHashrate == 0) return 0;
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    $dDifficulty = $this->getdifficulty();
    return $this->memcache->setCache(__FUNCTION__, $dDifficulty * pow(2,32) / $iCurrentPoolHashrate, 30);
  }
  public function getnetworkhashps() {
    $this->oDebug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    try {
      $dNetworkHashrate = $this->getmininginfo();
      if (is_array($dNetworkHashrate)) {
        if (array_key_exists('networkhashps', $dNetworkHashrate)) {
          $dNetworkHashrate = $dNetworkHashrate['networkhashps'];
        } else if (array_key_exists('hashespersec', $dNetworkHashrate)) {
          $dNetworkHashrate = $dNetworkHashrate['hashespersec'];
        } else if (array_key_exists('netmhashps', $dNetworkHashrate)) {
          $dNetworkHashrate = $dNetworkHashrate['netmhashps'] * 1000 * 1000;
        } else {
          // Unsupported implementation
          $dNetworkHashrate = 0;
        }
      }
    } catch (Exception $e) {
      // getmininginfo does not exist, cache for an hour
      return $this->memcache->setCache(__FUNCTION__, 0, 3600);
    }
    return $this->memcache->setCache(__FUNCTION__, $dNetworkHashrate, 30);
  }
}

// Load this wrapper
$bitcoin = new BitcoinWrapper($config['wallet']['type'], $config['wallet']['username'], $config['wallet']['password'], $config['wallet']['host'], $config['DEBUG'], $debug, $memcache);
