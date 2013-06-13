<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

if ($user->isAuthenticated()) {
  $aHourlyHashRates = $statistics->getHourlyHashrateByAccount($_SESSION['USERDATA']['id']);
  $aPoolHourlyHashRates = $statistics->getHourlyHashrateByPool();
  // Propagate content our template
  $smarty->assign("YOURHASHRATES", $aHourlyHashRates);
  $smarty->assign("POOLHASHRATES", $aPoolHourlyHashRates);
  $smarty->assign("CONTENT", "default.tpl");
}
?>
