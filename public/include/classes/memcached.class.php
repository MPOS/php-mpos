<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

/**
 * A wrapper class which provides compatibility between Memcached and Memcache
 * PHP uses the Memcached class on *nix environments, and the Memcache class
 * on Windows. This class provides compatibility between the two.
 **/
class Memcached
  extends Memcache
{
  public function set($key, $value, $expiration = 0)
  {
    return parent::set($key, $value, 0, $expiration);
  }
} 
