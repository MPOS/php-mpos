<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

if (@$_SESSION['USERDATA']['is_admin'] && $user->isAdmin(@$_SESSION['USERDATA']['id'])) {
  if (!include_once(INCLUDE_DIR . '/lib/jsonRPCClient.php')) die('Unable to load libs');
  $error = array();

  if ($config['skip_config_tests']) {
    $newerror = array();
    $newerror['name'] = "Config tests skipped";
    $newerror['description'] = "Config tests are disabled. Enable them in the global config to run them again.";
    $newerror['configvalue'] = "skip_config_tests";
    $newerror['helplink'] = "https://github.com/MPOS/php-mpos/wiki/Config-Setup#config-check";
    $error[] = $newerror;
    $newerror = null;
  } else {
    // setup some basic stuff for checking - getuid/getpwuid not available on mac/windows
    $apache_user = 'unknown';
    if (substr_count(strtolower(PHP_OS), 'nix') > 0 || substr_count(strtolower(PHP_OS), 'linux') > 0) {
      $apache_user = (function_exists('posix_getuid')) ? posix_getuid() : 'unknown';
      $apache_user = (function_exists('posix_getpwuid')) ? posix_getpwuid($apache_user) : $apache_user;
    }
    
    // we want to load anything in checks/ that is check_*.inc.php
    foreach(glob(__DIR__."/checks/check_*.inc.php") as $file) {
      include_once($file);
    }
  }
  $smarty->assign("ERRORS", $error);
}

$smarty->assign("CONTENT", "default.tpl");
