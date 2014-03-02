<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($user->isAuthenticated()) {
  if (! $interval = $setting->getValue('statistics_ajax_data_interval')) $interval = 300;
  // Defaults to get rid of PHP Notice warnings
  $dNetworkHashrate = 0;
  $dDifficulty = 1;
  $aRoundShares = 1;

  $aRoundShares = $statistics->getRoundShares();
  $dDifficulty = 1;
  $dNetworkHashrate = 1;
  $iBlock = 0;
  if ($bitcoin->can_connect() === true) {
    $dDifficulty = $bitcoin->getdifficulty();
    $dNetworkHashrate = $bitcoin->getnetworkhashps();
    $iBlock = $bitcoin->getblockcount();
  }

  // Fetch some data
  // Round progress
  $iEstShares = $statistics->getEstimatedShares($dDifficulty);
  if ($iEstShares > 0 && $aRoundShares['valid'] > 0) {
    $dEstPercent = round(100 / $iEstShares * $aRoundShares['valid'], 2);
  } else {
    $dEstPercent = 0;
  }
  if (!$iCurrentActiveWorkers = $worker->getCountAllActiveWorkers()) $iCurrentActiveWorkers = 0;
  $iCurrentPoolHashrate =  $statistics->getCurrentHashrate();
  $iCurrentPoolShareRate = $statistics->getCurrentShareRate();

  // Avoid confusion, ensure our nethash isn't higher than poolhash
  if ($iCurrentPoolHashrate > $dNetworkHashrate) $dNetworkHashrate = $iCurrentPoolHashrate;

  $dExpectedTimePerBlock = $statistics->getNetworkExpectedTimePerBlock();
  $dEstNextDifficulty = $statistics->getExpectedNextDifficulty();
  $iBlocksUntilDiffChange = $statistics->getBlocksUntilDiffChange();

  // Make it available in Smarty
  $smarty->assign('DISABLED_DASHBOARD', $setting->getValue('disable_dashboard'));
  $smarty->assign('DISABLED_DASHBOARD_API', $setting->getValue('disable_dashboard_api'));
  $smarty->assign('ESTIMATES', array('shares' => $iEstShares, 'percent' => $dEstPercent));
  $smarty->assign('NETWORK', array('difficulty' => $dDifficulty, 'block' => $iBlock, 'EstNextDifficulty' => $dEstNextDifficulty, 'EstTimePerBlock' => $dExpectedTimePerBlock, 'BlocksUntilDiffChange' => $iBlocksUntilDiffChange));
  $smarty->assign('INTERVAL', $interval / 60);
  $smarty->assign('CONTENT', 'default.tpl');
}

?>
