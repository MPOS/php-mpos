<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

/**
 * We use a wrapper class around BitcoinClient to add
 * some basic caching functionality and some debugging
 **/
class BitcoinWrapper extends BitcoinClient {
  public function __construct($type, $username, $password, $host, $debug, $memcache) {
    $this->type = $type;
    $this->username = $username;
    $this->password = $password;
    $this->host = $host;
    // $this->debug is already used
    $this->oDebug = $debug;
    $this->memcache = $memcache;
    return parent::__construct($this->type, $this->username, $this->password, $this->host);
  }
  /**
   * Wrap variouns methods to add caching
   **/
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
      $dNetworkHashrate = $this->query('getmininginfo');
      if (is_array($dNetworkHashrate) && array_key_exists('networkhashps', $dNetworkHashrate)) {
        $dNetworkHashrate = $dNetworkHashrate['networkhashps'];
      } else if (is_array($dNetworkHashrate) && array_key_exists('hashespersec', $dNetworkHashrate)) {
        $dNetworkHashrate = $dNetworkHashrate['hashespersec'];
      } else if (is_array($dNetworkHashrate) && array_key_exists('netmhashps', $dNetworkHashrate)) {
        $dNetworkHashrate = $dNetworkHashrate['netmhashps'] * 1000 * 1000;
      }
    } catch (Exception $e) {
      return false;
    }
    return $this->memcache->setCache(__FUNCTION__, $dNetworkHashrate, 30);
  }
}

// Load this wrapper
$bitcoin = new BitcoinWrapper($config['wallet']['type'], $config['wallet']['username'], $config['wallet']['password'], $config['wallet']['host'], $debug, $memcache);
