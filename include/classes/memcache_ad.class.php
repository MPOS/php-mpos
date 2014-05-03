<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class MemcacheAntiDos
{
  public $cache;
  public $rate_limit_this_request = false;
  public $rate_limit_api_request = false;
  public $rate_limit_site_request = false;
  public function __construct($config, &$memcache, $request='') {
    $this->cache = $memcache;
    // set our config options
    $userORip = $_SERVER['REMOTE_ADDR'].@$_SERVER['HTTP_USER_AGENT'];
    // prep stuff we need to check this request
    $key_md5 = $config['memcache']['keyprefix'].md5($userORip);
    $request_data = $this->cache->get($key_md5);
    $now = time();
    $max_req_flush = max(array($config['mc_antidos']['flush_seconds_api'],$config['mc_antidos']['flush_seconds_site']));
    // check the request
    if (is_array($request_data)) {
      // this request key already exists, update it
      $request_data['la'] = $now;
      if ($request == 'api') {
        $request_data['ha'] += 1;
        if ($config['mc_antidos']['ajax_hits_additive']) {
          $request_data['hn'] += 1;
        }
      } else {
        $request_data['hn'] += 1;
      }
      // not rate limited yet, update the rest of the object
      if (($request_data['hn'] < $config['mc_antidos']['rate_limit_site']) && ($request_data['ha'] < $config['mc_antidos']['rate_limit_api'])) {
        if (((($request_data['hnl'] + $config['mc_antidos']['flush_seconds_site']) <= $now) || ($request_data['hal'] + $config['mc_antidos']['flush_seconds_api']) <= $now) || (($request_data['la'] + $max_req_flush) <= $now)) {
          // needs to be flushed & updated
          $new = $this->getRequestBase();
          $new['key'] = $key_md5;
          $new['la'] = $now;
          $new['hal'] = ((($request_data['hal'] + $config['mc_antidos']['flush_seconds_api']) <= $now)) ? $now : 1;
          $new['hnl'] = ((($request_data['hnl'] + $config['mc_antidos']['flush_seconds_site']) <= $now)) ? $now : 1;
          $this->cache->set($key_md5, $new, $config['memcache']['expiration']);
          $this->rate_limit_api_request = ($request_data['ha'] >= $config['mc_antidos']['rate_limit_api']) ? true : false;
          $this->rate_limit_site_request = ($request_data['hn'] >= $config['mc_antidos']['rate_limit_site']) ? true : false;
        } else {
          // no flush, just update
          $new = $this->getRequestBase();
          $new['key'] = $request_data['key'];
          $new['la'] = time();
          $new['ha'] = $request_data['ha'];
          $new['hal'] = $request_data['hal'];
          $new['hn'] = $request_data['hn'];
          $new['hnl'] = $request_data['hnl'];
          $this->cache->set($key_md5, $new, $config['memcache']['expiration']);
          $this->rate_limit_api_request = ($request_data['ha'] >= $config['mc_antidos']['rate_limit_api']) ? true : false;
          $this->rate_limit_site_request = ($request_data['hn'] >= $config['mc_antidos']['rate_limit_site']) ? true : false;
        }
      } else {
        // too many hits, we should rate limit this
        $this->rate_limit_api_request = ($request_data['ha'] >= $config['mc_antidos']['rate_limit_api']) ? true : false;
        $this->rate_limit_site_request = ($request_data['hn'] >= $config['mc_antidos']['rate_limit_site']) ? true : false;
      }
    } else {
      // doesn't exist for this request_key, create one
      $new = $this->getRequestBase();
      $new['key'] = $config['memcache']['keyprefix'].md5($userORip);
      $new['la'] = time();
      if ($request == 'api') {
        $new['ha'] += 1;
        if ($config['mc_antidos']['ajax_hits_additive']) {
          $new['hn'] += 1;
        }
      } else {
        $new['hn'] += 1;
      }
      $this->cache->set($key_md5, $new, $config['memcache']['expiration']);
      $this->rate_limit_api_request = false;
      $this->rate_limit_site_request = false;
    }
  }
  public function getRequestBase() {
    $new = array('key' => '','la' => 0,'hn' => 0,'hnl' => 0,'ha' => 0,'hal' => 0);
    return $new;
  }
}

?>
