<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

if ($_REQUEST['do'] == 'save' && !empty($_REQUEST['data'])) {
  foreach($_REQUEST['data'] as $var => $value) {
    $setting->setValue($var, $value);
  }
  $_SESSION['POPUP'][] = array('CONTENT' => 'Settings updated');
}

// Fetch settings to propagate to template
$smarty->assign("MAINTENANCE", $setting->getValue('maintenance'));
$smarty->assign("REGISTRATION", $setting->getValue('registration'));

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
