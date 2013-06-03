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
    return $this->memcache->setCache(__FUNCTION__, parent::getblockcount());
  }
  public function getdifficulty() {
    $this->oDebug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    return $this->memcache->setCache(__FUNCTION__, parent::getdifficulty());
  }
  public function getestimatedtime($iCurrentPoolHashrate) {
    $this->oDebug->append("STA " . __METHOD__, 4);
    if ($iCurrentPoolHashrate == 0) return 0;
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    $dDifficulty = parent::getdifficulty();
    return $this->memcache->setCache(__FUNCTION__, $dDifficulty * pow(2,32) / $iCurrentPoolHashrate);
  }
}

// Load this wrapper
$bitcoin = new BitcoinWrapper($config['wallet']['type'], $config['wallet']['username'], $config['wallet']['password'], $config['wallet']['host'], $debug, $memcache);
