<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class MemcacheAntiDos
{
  public $cache;
  public $rate_limit_this_request = false;
  public $rate_limit_api_request = false;
  public $rate_limit_site_request = false;
  public function __construct($config, &$memcache, $userORip, $request='', $mcSettings) {
    $this->cache = $memcache;
    // set our config options
    $per_page = '';
    $flush_sec_api = $config['flush_seconds_api'];
    $rate_limit_api = $config['rate_limit_api'];
    $flush_sec_site = $config['flush_seconds_site'];
    $rate_limit_site = $config['rate_limit_site'];
    $ajax_add = $config['ajax_hits_additive'];
    unset($config);
    // prep stuff we need to check this request
    $key_md5 = md5($mcSettings['keyprefix'].$userORip);
    $request_data = $this->cache->get($key_md5);
    $now = time();
    $max_req_flush = max(array($flush_sec_api,$flush_sec_site));
    // check the request
    if (is_array($request_data)) {
      // this request key already exists, update it
      $request_data['la'] = $now;
      if ($request == 'api') {
        $request_data['ha'] += 1;
        if ($ajax_add) {
          $request_data['hn'] += 1;
        }
      } else {
        $request_data['hn'] += 1;
      }
      // not rate limited yet, update the rest of the object
      if (($request_data['hn'] < $rate_limit_site) && ($request_data['ha'] < $rate_limit_api)) {
        
        if (((($request_data['hnl'] + $flush_sec_site) <= $now) || ($request_data['hal'] + $flush_sec_api) <= $now) || (($request_data['la'] + $max_req_flush) <= $now)) {
          // needs to be flushed & updated
          $new = $this->getRequestBase();
          $new['key'] = $key_md5;
          $new['sid'] = session_id();
          $new['ua'] = md5($_SERVER['HTTP_USER_AGENT']);
          $new['ip'] = $key_md5;
          $new['la'] = $now;
          $new['hal'] = ((($request_data['hal'] + $flush_sec_api) <= $now)) ? $now : 1;
          $new['hnl'] = ((($request_data['hnl'] + $flush_sec_site) <= $now)) ? $now : 1;
          $this->cache->set($key_md5, $new, $max_req_flush);
          $this->rate_limit_api_request = ($request_data['ha'] >= $rate_limit_api) ? true : false;
          $this->rate_limit_site_request = ($request_data['hn'] >= $rate_limit_site) ? true : false;
          //$this->rate_limit_this_request = false;
        } else {
          // no flush, just update
          $new = $this->getRequestBase();
          $new['key'] = $key_md5;
          $new['sid'] = session_id();
          $new['ua'] = md5($_SERVER['HTTP_USER_AGENT']);
          $new['ip'] = $key_md5;
          $new['la'] = time();
          $new['ha'] = $request_data['ha'];
          $new['hal'] = $request_data['hal'];
          $new['hn'] = $request_data['hn'];
          $new['hnl'] = $request_data['hnl'];
          $this->cache->set($key_md5, $new, $max_req_flush);
          //$this->rate_limit_this_request = false;
          $this->rate_limit_api_request = ($request_data['ha'] >= $rate_limit_api) ? true : false;
          $this->rate_limit_site_request = ($request_data['hn'] >= $rate_limit_site) ? true : false;
        }
      } else {
        // too many hits, we should rate limit this
        //$this->rate_limit_this_request = true;
        $this->rate_limit_api_request = ($request_data['ha'] >= $rate_limit_api) ? true : false;
        $this->rate_limit_site_request = ($request_data['hn'] >= $rate_limit_site) ? true : false;
      }
    } else {
      // doesn't exist for this request_key, create one
      $new = $this->getRequestBase();
      $new['key'] = $key_md5;
      $new['sid'] = session_id();
      $new['ua'] = md5($_SERVER['HTTP_USER_AGENT']);
      $new['ip'] = $key_md5;
      $new['la'] = time();
      if ($request == 'api') {
        $new['ha'] += 1;
        if ($ajax_add) {
          $new['hn'] += 1;
        }
      } else {
        $new['hn'] += 1;
      }
      $this->cache->set($key_md5, $new, $max_req_flush);
      $this->rate_limit_this_request = false;
    }
  }
  public function getRequestBase() {
    $new = array(
        'key' => '',
        'sid' => '',
        'ua' => '',
        'ip' => '',
        'la' => 0,
        'hn' => 0,
        'hnl' => 0,
        'ha' => 0,
        'hal' => 0
    );
    return $new;
  }
  public function rateLimitRequest() {
    return $this->rate_limit_this_request;
  }
  public function rateLimitSite() {
    return $this->rate_limit_site_request;
  }
  public function rateLimitAPI() {
    return $this->rate_limit_api_request;
  }
}

?>