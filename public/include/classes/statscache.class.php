<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

/**
 * A wrapper class used to store values transparently in memcache
 * Can be enabled or disabled through site configuration
 * Also sets a default time if no time is passed to it to enforce caching
 **/
class StatsCache extends Memcached {
  public function __construct($config, $debug) {
    $this->config = $config;
    $this->debug = $debug;
    if (! $config['memcache']['enabled'] ) $this->debug->append("Not storing any values in memcache");
    return parent::__construct();
  }

  /**
   * Wrapper around memcache->set
   * Do not store values if memcache is disabled
   **/
  public function set($key, $value, $expiration=NULL) {
    if (! $this->config['memcache']['enabled']) return false;
    if (empty($expiration))
      $expiration = $this->config['memcache']['expiration'] + rand( -$this->config['memcache']['splay'], $this->config['memcache']['splay']);
    $this->debug->append("Storing " . $this->config['memcache']['keyprefix'] . "$key with expiration $expiration", 3);
    return parent::set($this->config['memcache']['keyprefix'] . $key, $value, $expiration);
  }

  /**
   * Wrapper around memcache->get
   * Always return false if memcache is disabled
   **/
  public function get($key, $cache_cb = NULL, &$cas_token = NULL) {
    if (! $this->config['memcache']['enabled']) return false;
    $this->debug->append("Trying to fetch key " . $this->config['memcache']['keyprefix'] . "$key from cache", 3);
    if ($data = parent::get($this->config['memcache']['keyprefix'].$key)) {
      $this->debug->append("Found key in cache", 3);
      return $data;
    } else {
      $this->debug->append("Key not found", 3);
    }
  }
}

$memcache = new StatsCache($config, $debug);
$memcache->addServer($config['memcache']['host'], $config['memcache']['port']);
