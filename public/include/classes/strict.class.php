<?php 
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class SessionManager {
  private $bind_address = '';
  private $started = false;
  private $host_verified = false;
  
  private $config_dura = 0;
  private $config_path = '';
  private $config_domain = '';
  private $config_secure = false;
  private $config_httponly = false;
  
  private $server_http_host = null;
  
  private $current_session_id = '';
  private $current_session_ip = '';
  
  public $memcache_handle = null;
  
  public function set_cookie_params($duration, $path, $domain, $secure, $httponly) {
    session_set_cookie_params((time()+$duration), $path, $domain, $secure, $httponly);
  }
  
  public function verify_server() {
    if ($this->bind_address !== $this->server_http_host) {
      return false;
    } else {
      return true;
    }
  }
  
  public function verify_client($ip) {
    if ($this->started && $this->memcache_handle !== null && $this->verify_server()) {
      $read_client = $this->memcache_handle->get(md5((string)$ip));
      if ($read_client !== false) {
        if (md5((string)$ip) !== $read_client[0]) {
          return false;
        } else {
          return true;
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  
  public function update_client($ip) {
    if ($this->started && $this->memcache_handle !== null && $this->verify_client($ip)) {
      $this->memcache_handle->set(md5((string)$ip), array($this->current_session_id, time()));
    }
  }
  
  public function set_cookie() {
    if ($this->started && $this->memcache_handle !== null && $this->verify_server() && $this->verify_client($ip)) {
      @setcookie(session_name(), session_id(), $this->config_dura, $this->config_path, $this->config_domain, $this->config_secure, $this->config_httponly);
    }
  }
  
  public function destroy_session($ip) {
    if ($this->started && $this->verify_server() && $this->verify_client($ip)) {
      $this->memcache_handle->delete(md5((string)$ip));
      if (ini_get('session.use_cookies')) {
        setcookie(session_name(), '', time() - 42000, $config_path, $config_domain, $config_secure, $config_httponly);
      }
      session_destroy();
      session_regenerate_id(true);
    }
  }
  
  public function create_session($ip) {
    if (!$this->verify_server()) {
      return false;
    } else {
      $session_start = @session_start();
      if (!$session_start) {
        session_destroy();
        session_regenerate_id(true);
        session_start();
        $this->update_client($ip);
        $this->started = true;
        $this->current_session_id = session_id();
        $this->set_cookie();
        return true;
      } else {
        if ($this->verify_server() && $this->verify_client($ip)) {
          $this->update_client($ip);
          return true;
        }
      }
    }
  }
  
  public function __construct($config, $server_host) {
    $this->config_dura = $config['cookie']['duration'];
    $this->config_path = $config['cookie']['path'];
    $this->config_domain = $config['cookie']['domain'];
    $this->config_secure = $config['cookie']['secure'];
    $this->config_httponly = $config['cookie']['httponly'];
    if ($config['strict__enforce_ssl']) $config['strict__bind_protocol'] = 'https';
    $this->bind_address = $config['strict__bind_protocol']."://".$config['strict__bind_host'].":".$config['strict__bind_port'];
    $this->server_http_host = $config['strict__bind_protocol']."://".$_SERVER['HTTP_HOST'].":".$config['strict__bind_port'];
    unset($config);
    $this->set_cookie_params((time()+$this->config_dura), $this->config_path, $this->config_domain, $this->config_secure, $this->config_httponly);
  }
}

class mysqli_strict extends mysqli {
  public function bind_param($paramTypes) {
    if (!is_string($paramTypes)) {
      return false;
    } else {
      $args = func_get_args();
      $acopy = $args;
      $nargs = count($args);
      for($i=1;$i<$nargs;$i++) {
        $pos = substr($paramTypes, ($i-1), 1);
        switch ($pos) {
        	case 's':
        	  $return_str = filter_var($acopy[$i], FILTER_VALIDATE_STRING, FILTER_NULL_ON_FAILURE);
        	  return ($return_str !== null) ? (string)$return_str : false;
        	  break;
        	case 'i':
        	  $return_int = filter_var($acopy[$i], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        	  return ($return_int !== null) ? (int)$return_int : false;
        	  break;
        	case 'd':
        	  $return_dbl = filter_var($acopy[$i], FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        	  return ($return_dbl !== null) ? (float)$return_dbl : false;
        	  break;
        	case 'b':
        	  $return_bool = filter_var($acopy[$i], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        	  return ($return_bool !== null) ? (bool)$return_bool : false;
        	  break;
        }
      }
    }
  }
}

?>