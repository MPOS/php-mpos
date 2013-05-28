<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class BitcoinWrapper extends BitcoinClient {
  var $type, $username, $password, $host, $memcache;
  public function __construct($type, $username, $password, $host, $memcache) {
    $this->type = $type;
    $this->username = $username;
    $this->password = $password;
    $this->host = $host;
    $this->memcache = $memcache;
    return parent::__construct($this->type, $this->username, $this->password, $this->host);
  }
  /**
   * Wrap variouns methods to add caching
   **/
  public function getblockcount() {
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    return $this->memcache->setCache(__FUNCTION__, parent::getblockcount());
  }
  public function getdifficulty() {
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    return $this->memcache->setCache(__FUNCTION__, parent::getdifficulty());
  }
  public function getestimatedtime($iCurrentPoolHashrate) {
    if ($iCurrentPoolHashrate == 0) return 0;
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    $dDifficulty = parent::getdifficulty();
    return $this->memcache->setCache(__FUNCTION__, $dDifficulty * pow(2,32) / $iCurrentPoolHashrate);
  }
}

// Load this wrapper
$bitcoin = new BitcoinWrapper($config['wallet']['type'], $config['wallet']['username'], $config['wallet']['password'], $config['wallet']['host'], $memcache);
