<?php

define('DB_VERSION', '0.0.1');
define('CONFIG_VERSION', '0.0.1');
define('MPOS_VERSION', '0.0.1');

// Fetch installed database version
$db_version = $setting->getValue('DB_VERSION');
if ($db_version != DB_VERSION) {
  $setting->setValue('db_upgrade_required', 1);
  // Notify admins via error popup
  if (isset($_SESSION['USERDATA']) && $user->isAdmin($_SESSION['USERDATA']['id']))
    $_SESSION['POPUP'][] = array('CONTENT' => 'Database version mismatch (Installed: ' . $db_version . ', Current: ' . DB_VERSION . '). Database update required, please import any new SQL files. Cronjobs have been halted.', 'TYPE' => 'errormsg');
}

if (@$config['version'] != CONFIG_VERSION) {
  $setting->setValue('config_upgrade_required', 1);
  // Notify admins via error popup
  if (isset($_SESSION['USERDATA']) && $user->isAdmin($_SESSION['USERDATA']['id']))
    $_SESSION['POPUP'][] = array('CONTENT' => 'Configuration file version mismatch (Installed: ' . @$config['version'] . ', Current: ' . CONFIG_VERSION . '). Configuration update required, please check dist config for changes. Cronjobs have been halted.', 'TYPE' => 'errormsg');
} else {
  // Reset option, maybe there is a better way?
  $setting->setValue('config_upgrade_required', 0);
}
