<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($user->isAuthenticated()) {
  if (! $interval = $setting->getValue('statistics_ajax_data_interval')) $interval = 300;
  // Defaults to get rid of PHP Notice warnings
  $dNetworkHashrate = 0;
  $dDifficulty = 1;
  $aRoundShares = 1;

  // Only run these if the user is logged in
  $aRoundShares = $statistics->getRoundShares();
  if ($bitcoin->can_connect() === true) {
    $dDifficulty = $bitcoin->getdifficulty();
    $dNetworkHashrate = $bitcoin->getnetworkhashps();
  }

  // Fetch some data
  if (!$iCurrentActiveWorkers = $worker->getCountAllActiveWorkers()) $iCurrentActiveWorkers = 0;
  $iCurrentPoolHashrate =  $statistics->getCurrentHashrate();
  $iCurrentPoolShareRate = $statistics->getCurrentShareRate();

  // Avoid confusion, ensure our nethash isn't higher than poolhash
  if ($iCurrentPoolHashrate > $dNetworkHashrate) $dNetworkHashrate = $iCurrentPoolHashrate;

  // Make it available in Smarty
  $smarty->assign('INTERVAL', $interval / 60);
  $smarty->assign('CONTENT', 'default.tpl');
}

?>
