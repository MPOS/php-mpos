<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

if (@$_REQUEST['do'] == 'save' && !empty($_REQUEST['data'])) {
  $user->log->log("warn", @$_SESSION['USERDATA']['username']." changed admin settings");
  foreach($_REQUEST['data'] as $var => $value) {
    $setting->setValue($var, $value);
  }
  $_SESSION['POPUP'][] = array('CONTENT' => 'Settings updated', 'TYPE' => 'alert alert-success');
}

// Load our available settings from configuration
require_once(INCLUDE_DIR . '/config/admin_settings.inc.php');

// Load onto the template
$smarty->assign("SETTINGS", $aSettings);

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
