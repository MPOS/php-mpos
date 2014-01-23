<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

class MemcacheAntiDos
{
  public $cache;
  public static $key = 'mcad_';
  public static $request_model = array(
  	'ident' => '',
    'last_hit' => 0,
    'last_flush' => 0,
    'hits_since_flush' => 0
  );
  public $rate_limit_this_request = false;
  public function __construct($config, $userORip, $request, $mcSettings) {
    if (PHP_OS == 'WINNT') {
      require_once('memcached.class.php');
    }
    $this->cache = new Memcached();
    $this->cache->addServer($mcSettings['host'], $mcSettings['port']);
    // set our config options
    $per_page = $config['per_page'];
    $flush_sec = $config['flush_seconds'];
    $rate_limit = $config['rate_limit'];
    unset($config);
    // prep stuff we need to check this request
    $key_md5 = substr(md5($userORip), 0, 4);
    $request_md5 = (!empty($per_page)) ? substr(md5($request), 0, 4) : '';
    $request_key = $mcSettings['keyprefix'].self::$key.$key_md5."_".$request_md5."_".$per_page;
    $request_data = $this->cache->get($request_key);
    $now = time();
    // check the request
    if (is_array($request_data)) {
      // this request key already exists, update it
      $request_data['ident'] = $key_md5;
      $request_data['last_hit'] = $now;
      $request_data['hits_since_flush'] += 1;
      // not rate limited yet, update the rest of the object
      if ($request_data['hits_since_flush'] < $rate_limit) {
        if (($request_data['last_flush'] + $flush_sec) <= $now || ($request_data['last_hit'] + $flush_sec) <= $now) {
          // needs to be flushed
          $request_data['hits_since_flush'] = 0;
          $request_data['last_hit'] = 0;
          $request_data['last_flush'] = $now;
          // update the object
          $this->cache->set($request_key, $request_data, $flush_sec);
          $this->rate_limit_this_request = false;
        } else {
          // no flush, just update
          $this->cache->set($request_key, $request_data, $flush_sec);
          $this->rate_limit_this_request = false;
        }
      } else {
        // too many hits, we should rate limit this
        $this->rate_limit_this_request = true;
      }
    } else {
      // doesn't exist for this request_key, create one
      $new_data = self::$request_model;
      $new_data['ident'] = $key_md5;
      $new_data['last_hit'] = time();
      $new_data['hits_since_flush'] = 1;
      $new_data['last_flush'] = $now;
      $this->cache->set($request_key, $new_data, $flush_sec);
      $this->rate_limit_this_request = false;
    }
  }
  public function rateLimitRequest() {
    return $this->rate_limit_this_request;
  }
}