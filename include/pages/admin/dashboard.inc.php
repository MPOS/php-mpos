<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

if ($bitcoin->can_connect() === true){
  $aGetInfo = $bitcoin->getinfo();
} else {
  $aGetInfo = array('errors' => 'Unable to connect');
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to wallet RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 'alert alert-danger');
}

// Grab versions from Online source
require_once(CLASS_DIR . '/tools.class.php');
$online_versions = $tools->getOnlineVersions();

// Fetch version information
$version['CURRENT'] = array('DB' => DB_VERSION, 'CONFIG' => CONFIG_VERSION, 'CORE' => MPOS_VERSION);
$version['INSTALLED'] = array('DB' => $setting->getValue('DB_VERSION'), 'CONFIG' => $config['version'], 'CORE' => $online_versions['MPOS_VERSION']);
$version['ONLINE'] = array('DB' => $online_versions['DB_VERSION'], 'CONFIG' => $online_versions['CONFIG_VERSION'], 'CORE' => $online_versions['MPOS_VERSION']);

// Fetch our cron list $aMonitorCrons
require_once(INCLUDE_DIR . '/config/monitor_crons.inc.php');

// Data array for template
$cron_errors = 0;
$cron_disabled = 0;
foreach ($aMonitorCrons as $strCron) {
  $status = $monitoring->getStatus($strCron . '_status');
  if ($status['value'] != 0)
    $cron_errors++;
  if ($monitoring->isDisabled($strCron) == 1)
    $cron_disabled++;
}
$smarty->assign('CRON_ERROR', $cron_errors);
$smarty->assign('CRON_DISABLED', $cron_disabled);

// Fetch user information
$aUserInfo = array(
  'total' => $user->getCount(),
  'active' => $statistics->getCountAllActiveUsers(),
  'locked' => $user->getCountFiltered('is_locked', 1),
  'admins' => $user->getCountFiltered('is_admin', 1),
  'nofees' => $user->getCountFiltered('no_fees', 1)
);
$smarty->assign('USER_INFO', $aUserInfo);

// Fetch login information
$aLoginInfo = array(
  '24hours' => $user->getCountFiltered('last_login', time() - 86400, 'i', '>='),
  '7days' => $user->getCountFiltered('last_login', (time() - (86400 * 7)), 'i', '>='),
  '1month' => $user->getCountFiltered('last_login', (time() - (86400 * 7 * 4)), 'i', '>='),
  '6month' => $user->getCountFiltered('last_login', (time() - (86400 * 7 * 4 * 6)), 'i', '>='),
  '1year' => $user->getCountFiltered('last_login', (time() - (86400 * 365)), 'i', '>=')
);
$smarty->assign('USER_LOGINS', $aLoginInfo);

// Fetch registration information
$aRegistrationInfo = array(
  '24hours' => $user->getCountFiltered('signup_timestamp', time() - 86400, 'i', '>='),
  '7days' => $user->getCountFiltered('signup_timestamp', (time() - (86400 * 7)), 'i', '>='),
  '1month' => $user->getCountFiltered('signup_timestamp', (time() - (86400 * 7 * 4)), 'i', '>='),
  '6month' => $user->getCountFiltered('signup_timestamp', (time() - (86400 * 7 * 4 * 6)), 'i', '>='),
  '1year' => $user->getCountFiltered('signup_timestamp', (time() - (86400 * 365)), 'i', '>=')
);
$smarty->assign('USER_REGISTRATIONS', $aRegistrationInfo);

// Fetching invitation Informations
if (!$setting->getValue('disable_invitations')) {
  // Fetch global invitation information
  $aInvitationInfo = array(
    'total' => $invitation->getCount(),
    'activated' => $invitation->getCountFiltered('is_activated', 1),
    'outstanding' => $invitation->getCountFiltered('is_activated', 0)
  );
  $smarty->assign('INVITATION_INFO', $aInvitationInfo);
}

// Wallet status
$smarty->assign('WALLET_ERROR', $aGetInfo['errors']);

// Tempalte specifics
$smarty->assign('VERSION', $version);
$smarty->assign("CONTENT", "default.tpl");
?>
