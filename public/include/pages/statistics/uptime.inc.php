<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  if ($setting->getValue('monitoring_uptimerobot_private_key')) {
    if ($aStatus = $monitoring->getUptimeRobotStatus()) {
      $smarty->assign("STATUS", $aStatus);
      $smarty->assign("CODES", array(
        0 => 'Paused',
        1 => 'Unchecked',
        2 => 'Up',
        8 => 'Down',
        9 => 'Down'
      ));
    }
    $smarty->assign("CONTENT", "default.tpl");
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => 'UptimeRobot API Key not configured.', 'TYPE' => 'info');
    $smarty->assign("CONTENT", "");
  }
} else {
  $debug->append('Using cached page', 3);
}

?>
