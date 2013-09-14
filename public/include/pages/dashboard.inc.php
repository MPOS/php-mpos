<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($user->isAuthenticated()) {
  if (! $interval = $setting->getValue('statistics_ajax_data_interval')) $interval = 300;
  // Defaults to get rid of PHP Notice warnings
  $dDifficulty = 1;
  $aRoundShares = 1;

  // Only run these if the user is logged in
  $aRoundShares = $statistics->getRoundShares();
  if ($bitcoin->can_connect() === true) {
    $dDifficulty = $bitcoin->query('getdifficulty');
    if (is_array($dDifficulty) && array_key_exists('proof-of-work', $dDifficulty))
      $dDifficulty = $dDifficulty['proof-of-work'];
  }

  // Always fetch this since we need for ministats header
  $aRoundShares = $statistics->getRoundShares();
  if ($bitcoin->can_connect() === true) {
    $dDifficulty = $bitcoin->query('getdifficulty');
    if (is_array($dDifficulty) && array_key_exists('proof-of-work', $dDifficulty))
      $dDifficulty = $dDifficulty['proof-of-work'];
    try { $dNetworkHashrate = $bitcoin->query('getnetworkhashps') / 1000; } catch (Exception $e) {
      // Maybe we are SHA
      try { $dNetworkHashrate = $bitcoin->query('gethashespersec') / 1000; } catch (Exception $e) {
        $dNetworkHashrate = 0;
      }
      $dNetworkHashrate = 0;
    }
  } else {
    $dNetworkHashrate = 0;
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
