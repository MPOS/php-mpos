<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

define('MPOS_VERSION', '0.0.2');
define('DB_VERSION', '0.0.4');
define('CONFIG_VERSION', '0.0.6');

// Fetch installed database version
$db_version = $setting->getValue('DB_VERSION');
if ($db_version != DB_VERSION) {
  // Notify admins via error popup
  if (isset($_SESSION['USERDATA']) && $user->isAdmin($_SESSION['USERDATA']['id']))
    $_SESSION['POPUP'][] = array('CONTENT' => 'Database version mismatch (Installed: ' . $db_version . ', Current: ' . DB_VERSION . '). Database update required, please import any new SQL files. Cronjobs have been halted.', 'TYPE' => 'errormsg');
}

if (@$config['version'] != CONFIG_VERSION) {
  // Notify admins via error popup
  if (isset($_SESSION['USERDATA']) && $user->isAdmin($_SESSION['USERDATA']['id']))
    $_SESSION['POPUP'][] = array('CONTENT' => 'Configuration file version mismatch (Installed: ' . @$config['version'] . ', Current: ' . CONFIG_VERSION . '). Configuration update required, please check dist config for changes. Cronjobs have been halted.', 'TYPE' => 'errormsg');
}
