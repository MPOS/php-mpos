<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

if ($bitcoin->can_connect() === true){
  $aGetInfo = $bitcoin->query('getinfo');
} else {
  $aGetInfo = array('errors' => 'Unable to connect');
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to wallet RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
}

// Fetch version information
$version['CURRENT'] = array('DB' => DB_VERSION, 'CONFIG' => CONFIG_VERSION, 'CORE' => MPOS_VERSION);
$version['INSTALLED'] = array('DB' => $setting->getValue('DB_VERSION'), 'CONFIG' => $config['version'], 'CORE' => MPOS_VERSION);

// Fetch cron information
$aCrons = array('statistics','payouts','token_cleanup','archive_cleanup','blockupdate','findblock','notifications','tickerupdate');
// Data array for template
$cron_errors = 0;
$cron_disabled = 0;
foreach ($aCrons as $strCron) {
  $status = $monitoring->getStatus($strCron . '_status');
  $disabled = $monitoring->isDisabled($strCron);
  if ($status['value'] != 0)
    $cron_errors++;
  if ($disabled['value'] == 1)
    $cron_disabled++;
}
$smarty->assign('CRON_ERROR', $cron_errors);
$smarty->assign('CRON_DISABLED', $cron_disabled);

// Fetch user information
$aUserInfo = array(
  'total' => $user->getCount(),
  'locked' => $user->getCountFiltered('is_locked', 1),
  'admins' => $user->getCountFiltered('is_admin', 1),
  'nofees' => $user->getCountFiltered('no_fees', 1)
);
$smarty->assign('USER_INFO', $aUserInfo);

// Wallet status
$smarty->assign('WALLET_ERROR', $aGetInfo['errors']);

// Tempalte specifics
$smarty->assign('VERSION', $version);
$smarty->assign("CONTENT", "default.tpl");
?>
