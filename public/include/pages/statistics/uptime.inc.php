<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  if ($setting->getValue('monitoring_uptimerobot_api_keys') && $aStatus = $monitoring->getUptimeRobotStatus()) {
    $smarty->assign("STATUS", $aStatus);
    $smarty->assign("UPDATED", $setting->getValue('monitoring_uptimerobot_lastcheck'));
    $smarty->assign("CODES", array(
      0 => 'Paused',
      1 => 'Unchecked',
      2 => 'Up',
      8 => 'Down',
      9 => 'Down'
    ));
    $content = 'default.tpl';
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => 'UptimeRobot API Key not configured.', 'TYPE' => 'alert alert-warning');
    $content = '';
  }
} else {
  $debug->append('Using cached page', 3);
}

switch($setting->getValue('acl_uptime_statistics', 1)) {
case '0':
  if ($user->isAuthenticated()) {
    $smarty->assign("CONTENT", $content);
  }
  break;
case '1':
  $smarty->assign("CONTENT", $content);
  break;
case '2':
  $_SESSION['POPUP'][] = array('CONTENT' => 'Page currently disabled. Please try again later.', 'TYPE' => 'alert alert-danger');
  $smarty->assign("CONTENT", "");
  break;
}
