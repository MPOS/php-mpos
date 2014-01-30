<?php 
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class strict_session {
  private $memcache = null;
  private $validate_client = false;
  private $validate_client_ip = false;
  private $validate_client_ua = false;
  private $validate_client_sid = false;
  private $validate_client_num = 0;
  private $valid_server = '';
  private $memcache_key = '';
  public function valid_session_id($id) {
    return preg_match('#^[a-zA-Z0-9]{26}$#', $id);
  }
  public function session_delete_key($key) {
    $read = $this->memcache->delete($key);
  }
  private $validation_misses = 0;
  private $initial_ua;
  public function create_or_update_client($client, $force=false, $login=false) {
    $read = $this->memcache->get($client['key']);
    // this needs to be available later
    $update = array('key' => '','sid' => '','ua' => '','ip' => '','la' => 0,'hn' => 0,'hnl' => 0,'ha' => 0,'hal' => 0);
    $update['sid'] = $client['sid'];
    $update['ua'] = md5($this->initial_ua);
    $update['ip'] = $client['ip'];
    $update['la'] = time();
    $update['key'] = md5($this->memcache_key.$client['ip']);
    $validation_misses = 0;
    if ($read !== false) {
      $read_model = array('key' => '','sid' => '','ua' => '','ip' => '','la' => 0,'hn' => 0,'hnl' => 0,'ha' => 0,'hal' => 0);
      $read_model['sid'] = @$read['sid'];
      $read_model['ip'] = @$read['ip'];
      $read_model['ua'] = @$read['ua'];
      $read_model['la'] = @$read['la'];
      $read_model['key'] = md5($this->memcache_key.$read['ip']);
      // key already exists, update
      if ($this->validate_client) {
        if ($this->verify_client($read_model, $update, $login)) {
          $update_client = $this->memcache->set($update['key'], $update);
        }
      }
    } else {
      $update_client = $this->memcache->set($client['key'], $client);
      if ($force && $login) {
        $update_client = $this->memcache->set($update['key'], $update);
      }
    }
  }
  public function verify_client($client_model, $data, $login=false) {
    $fails = 0;
    $fails += ((count($client_model)) !== (count($data)) && $this->validate_client) ? 1 : 0;
    $fails += ($client_model['ua'] !== $data['ua'] && $this->validate_client && $this->validate_client_ua) ? 1 : 0;
    $fails += ($client_model['ip'] !== $data['ip'] && $this->validate_client && $this->validate_client_ip) ? 1 : 0;
    $now = time();
    $this->validation_misses = $fails;
    if ($fails > $this->validate_client_num && $login == false && $this->validate_client) {
      // something changed
      $port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : (":".$_SERVER["SERVER_PORT"]);
      $location = (@$_SERVER['HTTPS'] == "on") ? 'https://' : 'http://';
      $location .= $_SERVER['SERVER_NAME'] . $port . $_SERVER['SCRIPT_NAME'];
      $this->session_delete_key($client_model['key']);
      $this->session_delete_key($data['key']);
      @session_start();
      @session_regenerate_id(true);
      $_SESSION = null;
      $_SESSION['POPUP'][] = array('CONTENT' => "Session revoked due to a change in your client. You may have a plugin messing with your useragent, or your IP address may have changed.", 'TYPE' => 'warning');
      $location.= '?page=login';
      if (!headers_sent()) exit(header('Location: ' . $location));
      exit('<meta http-equiv="refresh" content="0; url=' . htmlspecialchars($location) . '"/>');
    }
    return ($fails > 0) ? false : true;
  }
  public function read_if_client_exists($client_key) {
    if ($this->memcache !== null) {
      $exists = $this->memcache->get($client_key);
    }
    return ($exists !== null) ? $exists : false;
  }
  public function regen_session_id() {
    $sidbefore = @session_id();
    @session_regenerate_id(true);
    $sid = session_id();
    return $sid;
  }
  public function __construct($config, &$memcache) {
    $this->initial_ua = $_SERVER['HTTP_USER_AGENT'];
    $this->memcache = $memcache;
    $this->memcache_key = $config['memcache']['keyprefix'];
    if ($config['strict__verify_client']) {
      $this->validate_client = true;
      $this->validate_client_ip = $config['strict__verify_client_ip'];
      $this->validate_client_ua = $config['strict__verify_client_useragent'];
      $this->validate_client_sid = $config['strict__verify_client_sessionid'];
      $this->validate_client_num = 0;
      if ($config['strict__verify_server']) {
        $proto = (@$_SERVER['HTTPS'] == "on") ? 'https' : 'http';
        $location = $proto."://".$_SERVER['SERVER_NAME'] . $_SERVER['SERVER_PORT'];
        if ($config['strict__verify_server']) {
          if ($config['strict__bind_protocol']."://".$config['strict__bind_host'].$config['strict__bind_port'] !== $location) {
            return false;
          }
        }
      }
      $client = array('key' => '','sid' => '','ua' => '','ip' => '','la' => 0,'hn' => 0,'hnl' => 0,'ha' => 0,'hal' => 0);
      $client['ua'] = md5($_SERVER['HTTP_USER_AGENT']);
      $client['ip'] = md5($_SERVER['REMOTE_ADDR']);
      $client['la'] = time();
      $client['key'] = md5($this->memcache_key.$client['ip']);
      $read = $this->read_if_client_exists($client['key']);
    }
    session_set_cookie_params((time()+$config['cookie']['duration']), $config['cookie']['path'], $config['cookie']['domain'], false, true);
    $session_start = @session_start();
    $client['sid'] = session_id();
    $valid_session_id = $this->valid_session_id($client['sid']);
    if (!$valid_session_id || !$session_start) {
      @session_destroy();
      $client['sid'] = $this->regen_session_id();
      session_start();
    }
    if ($read !== null) {
      // client exists, verify
      $this->create_or_update_client($client, true, false);
      
    } else {
      // doesn't exist
      $this->create_or_update_client($client, true, true);
    }
    @setcookie(session_name(), $client['sid'], (time()+$config['cookie']['duration']), $config['cookie']['path'], $config['cookie']['domain'], false, true);
    // post changes validate
    if ($this->validate_client) {
      $read_post = $this->read_if_client_exists($client['key']);
      if ($read_post !== null) {
        $this->verify_client($client, $read_post, true);
      }
    }
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
        $ipos = ($i-1);
        $pos = substr($paramTypes, $ipos, 1);
        switch ($pos) {
        	case 's':
        	  $return_str = filter_var($acopy[$i], FILTER_VALIDATE_STRING, FILTER_NULL_ON_FAILURE);
        	  $acopy[$i] = ($return_str !== null) ? (string)$return_str : null;
        	  break;
        	case 'i':
        	  $return_int = filter_var($acopy[$i], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        	  $acopy[$i] = ($return_int !== null) ? (int)$return_int : null;
        	  break;
        	case 'd':
        	  $return_dbl = filter_var($acopy[$i], FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        	  $acopy[$i] = ($return_dbl !== null) ? (float)$return_dbl : null;
        	  break;
        	case 'b':
        	  $return_bool = filter_var($acopy[$i], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        	  $acopy[$i] = ($return_bool !== null) ? (bool)$return_bool : null;
        	  break;
        }
      }
      return (in_array(null, $acopy));
    }
  }
}

?>