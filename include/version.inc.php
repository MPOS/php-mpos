<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

define('MPOS_VERSION', '1.1.0');
define('DB_VERSION', '1.0.3');
define('CONFIG_VERSION', '1.0.1');
define('HASH_VERSION', 1);

// Fetch installed database version
$db_version = $setting->getValue('DB_VERSION');
if ($db_version != DB_VERSION) {
  // Notify admins via error popup
  $_SESSION['POPUP'][] = array('CONTENT' => 'Database version mismatch (Installed: ' . $db_version . ', Current: ' . DB_VERSION . '). Database update required, please import any new SQL files. Cronjobs have been halted.', 'TYPE' => 'alert alert-danger');
}
if (@$config['version'] !== CONFIG_VERSION) {
  // Notify admins via error popup
  $_SESSION['POPUP'][] = array('CONTENT' => 'Configuration file version mismatch (Installed: ' . @$config['version'] . ', Current: ' . CONFIG_VERSION . '). Configuration update required, please check dist config for changes. Cronjobs have been halted.', 'TYPE' => 'alert alert-danger');
}
