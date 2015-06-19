<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($config['logging']['enabled']) {
  // checks to see that the logging path is writable
  if (!is_writable($config['logging']['path'])) {
    $newerror = array();
    $newerror['name'] = "Log path permissions";
    $newerror['level'] = 3;
    $newerror['extdesc'] = "In order to log data, we need to be able to write in the logs folder. See the link above for more details.";
    $newerror['description'] = "Logging is enabled but we can't write in the logfile path.";
    $newerror['configvalue'] = "logging.path";
    $newerror['helplink'] = "https://github.com/MPOS/php-mpos/wiki/Quick-Start-Guide#configuration-1";
    $error[] = $newerror;
    $newerror = null;
  }
}

// check if we can write templates/cache and templates/compile -> error
if (!is_writable(TEMPLATE_DIR . '/cache')) {
  $newerror = array();
  $newerror['name'] = "templates/cache permissions";
  $newerror['level'] = 3;
  $newerror['extdesc'] = "In order to cache template data, we need to be able to write in the templates/cache folder. See the link above for more details.";
  $newerror['description'] = "templates/cache folder is not writable for uid {$apache_user['name']}";
  $newerror['configvalue'] = "templates/cache folder";
  $newerror['helplink'] = "https://github.com/MPOS/php-mpos/wiki/Quick-Start-Guide#folder-permissions";
  $error[] = $newerror;
  $newerror = null;
}
if (!is_writable(TEMPLATE_DIR . '/compile')) {
  $newerror = array();
  $newerror['name'] = "templates/compile permissions";
  $newerror['level'] = 3;
  $newerror['extdesc'] = "In order to cache compiled template data, we need to be able to write in the templates/compile folder. See the link above for more details.";
  $newerror['description'] = "templates/compile folder is not writable for uid {$apache_user['name']}";
  $newerror['configvalue'] = "templates/compile folder";
  $newerror['helplink'] = "https://github.com/MPOS/php-mpos/wiki/Quick-Start-Guide#folder-permissions";
  $error[] = $newerror;
  $newerror = null;
}

// check if we can write the config files, we should NOT be able to -> error
if (is_writable(INCLUDE_DIR.'/config/global.inc.php') || is_writable(INCLUDE_DIR.'/config/global.inc.dist.php') ||
  is_writable(INCLUDE_DIR.'/config/security.inc.php') || is_writable(INCLUDE_DIR.'/config/security.inc.dist.php')) {
  $newerror = array();
  $newerror['name'] = "Config permissions";
  $newerror['level'] = 2;
  $newerror['extdesc'] = "For security purposes, the user your webserver runs as should not be able to write to the config files, only read from them. To fix this, check the ownership and permissions of the include/config files.";
  $newerror['description'] = "Your config files <b>SHOULD NOT be writable by this user</b>!";
  $newerror['configvalue'] = "global.inc.php and security.inc.php";
  $newerror['helplink'] = "https://github.com/MPOS/php-mpos/wiki/Quick-Start-Guide#configuration-1";
  $error[] = $newerror;
  $newerror = null;
}
