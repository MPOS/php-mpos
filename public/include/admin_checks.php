<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if (@$_SESSION['USERDATA']['is_admin'] && $user->isAdmin(@$_SESSION['USERDATA']['id'])) {
  if (!include_once(INCLUDE_DIR . '/lib/jsonRPCClient.php')) die('Unable to load libs');
  $notice = array();
  $enotice = array();
  $error = array();
  
  // setup some basic stuff for checking - getuid/getpwuid not available on mac/windows
  $apache_user = 'unknown';
  if (substr_count(strtolower(PHP_OS), 'nix') > 0) {
    $apache_user = (function_exists('posix_getuid')) ? posix_getuid() : 'unknown';
    $apache_user = (function_exists('posix_getpwuid')) ? posix_getpwuid($apache_user) : $apache_user;
  }
  
  // setup checks
  // logging
  if ($config['logging']['enabled']) {
    if (!is_writable($config['logging']['path'])) {
      $error[] = "Logging is enabled but we can't write in the logging path";
    }
  }
  // check if memcache isn't available but enabled in config -> error
  if (!class_exists('Memcached') && $config['memcache']['enabled']) {
    $error[] = "You have memcache enabled in your config and it's not available. Install the package on your system.";
  }
  // if it's not enabled, test it if it exists, if it works -> error tell them to enable, -> otherwise notice it's disabled
  if (!$config['memcache']['enabled']) {
    if (PHP_OS == 'WINNT') {
      require_once(CLASS_DIR . 'memcached.class.php');
    }
    if (class_exists('Memcached')) {
      $memcache_test = @new Memcached();
      $memcache_test_add = @$memcache_test->addServer($config['memcache']['host'], $config['memcache']['port']);
      $randmctv = rand(5,10);
      $memcache_test_set = @$memcache_test->set('test_mpos_setval', $randmctv);
      $memcache_test_get = @$memcache_test->get('test_mpos_setval');
    }
    if (class_exists('Memcached') && $memcache_test_get == $randmctv) {
      $error[] = "You have memcache disabled in the config and it's available & works! Enable it.";
    } else {
      $notice[] = "Memcache is disabled; Almost every linux distro has packages for it, you should be using it if you can.";
    }
  }
  // check if htaccess exists
  if (!file_exists(BASEPATH.".htaccess")) {
    $htaccess_link = "<a href='https://github.com/MPOS/php-mpos/blob/next/public/.htaccess'>.htaccess</a>";
    $notice[] = "You don't seem to have a .htaccess in your public folder, if you're using apache set it up $htaccess_link";
  }
  // check if we can write templates/cache and templates/compile -> error
  if (!is_writable(THEME_DIR.'/cache')) {
    $error[] = "templates/cache folder is not writable for uid {$apache_user['name']}";
  }
  if (!is_writable(THEME_DIR.'/compile')) {
    $error[] = "templates/compile folder is not writable for uid {$apache_user['name']}";
  }
  // check if we can write the config files, we should NOT be able to -> error
  if (is_writable(INCLUDE_DIR.'/config/global.inc.php') || is_writable(INCLUDE_DIR.'/config/global.inc.dist.php') ||
      is_writable(INCLUDE_DIR.'/config/security.inc.php') || is_writable(INCLUDE_DIR.'/config/security.inc.dist.php')) {
    $error[] = "Your config files <b>SHOULD NOT be writable to this user</b>!";
  }
  // check if daemon can connect -> error
  try {
    if ($bitcoin->can_connect() !== true) {
      $error[] = "Unable to connect to coin daemon using provided credentials";
    }
    else {
      // validate that the wallet service is not in test mode
      if ($bitcoin->is_testnet() == true) {
        $error[] = "The coin daemon service is running as a testnet. Check the TESTNET setting in your coin daemon config and make sure the correct port is set in the MPOS config.";
      }

      // if coldwallet is not empty, check if the address is valid -> error
      if (!empty($config['coldwallet']['address'])) {
        if (!$bitcoin->validateaddress($config['coldwallet']['address']))
          $error[] = "Your cold wallet address is <u>SET and INVALID</u>";
      }
      
      // check if there is more than one account set on wallet
      $accounts = $bitcoin->listaccounts();
      if (count($accounts) > 1 && $accounts[''] <= 0) {
        $error[] = "There are " . count($accounts) . " Accounts set in local Wallet and Default Account has no liquid funds to pay your miners!";
      }
    }
  } catch (Exception $e) {
  }
  // if database connection fails -> error
  $db_connect = new mysqli($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['name'], $config['db']['port']);
  if (mysqli_connect_errno() || !array_key_exists('client_info', $db_connect)) {
    $error[] = "Unable to connect to mysql using provided credentials";
  }
  if ($config['mc_antidos'] && !$config['memcache']['enabled']) {
    $error[] = "mc_antidos is enabled and memcache is not, <u>memcache is required</u> to use this";
  }
  // poke stratum using gettingstarted details -> enotice
  if (substr_count(strtolower(PHP_OS), 'nix') > 0) {
    // unix *poke*
    $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket !== false) {
      $address = @gethostbyname($config['gettingstarted']['stratumurl']);
      $result = @socket_connect($socket, $address, $config['gettingstarted']['stratumport']);
      if ($result !== 1) {
        $enotice[] = "We tried to poke your Stratum server using config->gettingstarted details but it didn't respond";
      }
      $close = @socket_close($socket);
    }
  } else {
    // mac/windows *poke*
    if (! $fp = @fsockopen($config['gettingstarted']['stratumurl'],$config['gettingstarted']['stratumport'],$errCode,$errStr,1)) {
      $enotice[] = "We tried to poke your Stratum server using config->gettingstarted details but it didn't respond";
    }
    @fclose($fp);
  }
  
  // security checks
  // salts too short -> notice, salts default -> error
  if ((strlen($config['SALT']) < 24) || (strlen($config['SALTY']) < 24) || $config['SALT'] == 'PLEASEMAKEMESOMETHINGRANDOM' || $config['SALTY'] == 'THISSHOULDALSOBERRAANNDDOOM') {
    if ($config['SALT'] == 'PLEASEMAKEMESOMETHINGRANDOM' || $config['SALTY'] == 'THISSHOULDALSOBERRAANNDDOOM') {
      $error[] = "You absolutely <u>SHOULD NOT leave your SALT or SALTY default</u> changing them will require registering again";
    } else {
      $notice[] = "SALT or SALTY is too short, they should be more than 24 characters and changing them will require registering again";
    }
  }
  
  // display the errors
  foreach ($enotice as $en) {
    $_SESSION['POPUP'][] = array('CONTENT' => $en, 'TYPE' => 'info');
  }
  if (!count($notice) && !count($error)) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'The config options we checked seem OK', 'TYPE' => 'success');
  } else {
    foreach ($notice as $n) {
      $_SESSION['POPUP'][] = array('CONTENT' => $n, 'TYPE' => 'warning');
    }
    foreach ($error as $e) {
      $_SESSION['POPUP'][] = array('CONTENT' => $e, 'TYPE' => 'errormsg');
    }
  }
}

?>
