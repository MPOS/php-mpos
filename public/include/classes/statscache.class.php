<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

/**
 * A wrapper class used to store values transparently in memcache
 * Can be enabled or disabled through site configuration
 * Also sets a default time if no time is passed to it to enforce caching
 **/
class StatsCache {
  private $cache, $round;

  public function __construct($config, $debug) {
    $this->config = $config;
    $this->debug = $debug;
    if (! $config['memcache']['enabled'] ) {
      $this->debug->append("Not storing any values in memcache");
    } else {
      if (PHP_OS == 'WINNT') {
        require_once(CLASS_DIR . '/memcached.class.php');
      }
      $this->cache = new Memcached();
    }
  }

  public function setRound($round_id) {
    $this->round = $round_id;
  }
  public function getRound() {
    return $this->round;
  }

  /**
   * Wrapper around memcache->set
   * Do not store values if memcache is disabled
   **/
  public function set($key, $value, $expiration=NULL) {
    if (! $this->config['memcache']['enabled']) return $value;
    if (empty($expiration))
      $expiration = $this->config['memcache']['expiration'] + rand( -$this->config['memcache']['splay'], $this->config['memcache']['splay']);
    $this->debug->append("Storing " . $this->getRound() . '_' . $this->config['memcache']['keyprefix'] . "$key with expiration $expiration", 3);
    return $this->cache->set($this->getRound() . '_' . $this->config['memcache']['keyprefix'] . $key, $value, $expiration);
  }

  /**
   * Special memcache->set call bypassing any auto-expiration systems
   * Can be used as a static, auto-updated cache via crons
   **/
  public function setStaticCache($key, $value, $expiration=NULL) {
    if (! $this->config['memcache']['enabled']) return $value;
    if (empty($expiration))
      $expiration = $this->config['memcache']['expiration'] + rand( -$this->config['memcache']['splay'], $this->config['memcache']['splay']);
    $this->debug->append("Storing " . $this->config['memcache']['keyprefix'] . "$key with expiration $expiration", 3);
    if ($this->cache->set($this->config['memcache']['keyprefix'] . $key, $value, $expiration))
      return $value;
    return false;
  }

  /**
   * Wrapper around memcache->get
   * Always return false if memcache is disabled
   **/
  public function get($key, $cache_cb = NULL, &$cas_token = NULL) {
    if (! $this->config['memcache']['enabled']) return false;
    $this->debug->append("Trying to fetch key " . $this->getRound() . '_' . $this->config['memcache']['keyprefix'] . "$key from cache", 3);
    if ($data = $this->cache->get($this->getRound() . '_' . $this->config['memcache']['keyprefix'].$key)) {
      $this->debug->append("Found key in cache", 3);
      return $data;
    } else {
      $this->debug->append("Key not found", 3);
    }
  }

  /**
   * As the static set call, we try to fetch static data here
   **/
  public function getStatic($key, $cache_cb = NULL, &$cas_token = NULL) {
    if (! $this->config['memcache']['enabled']) return false;
    $this->debug->append("Trying to fetch key " . $this->config['memcache']['keyprefix'] . "$key from cache", 3);
    if ($data = $this->cache->get($this->config['memcache']['keyprefix'].$key)) {
      $this->debug->append("Found key in cache", 3);
      return $data;
    } else {
      $this->debug->append("Key not found", 3);
    }
  }

  /**
   * Another wrapper, we want to store data in memcache and return the actual data
   * for further processing
   * @param key string Our memcache key
   * @param data mixed Our data to store in Memcache
   * @param expiration time Our expiration time, see Memcached documentation
   * @return data mixed Return our stored data unchanged
   **/
  public function setCache($key, $data, $expiration=NULL) {
    if ($this->config['memcache']['enabled']) $this->set($key, $data, $expiration);
    return $data;
  }

  /**
   * This method is invoked if the called method was not realised in this class
   **/
  public function __call($name, $arguments) {
    if (! $this->config['memcache']['enabled']) return false;
    //Invoke method $name of $this->cache class with array of $arguments
    return call_user_func_array(array($this->cache, $name), $arguments);
  }
}

$memcache = new StatsCache($config, $debug);
$memcache->addServer($config['memcache']['host'], $config['memcache']['port']);
// Now we can set our additional key prefix
if ($aTmpBlock = $block->getLast()) {
  $iRoundId = $aTmpBlock['id'];
} else {
  $iRoundId = 0;
}
$memcache->setRound($iRoundId);
