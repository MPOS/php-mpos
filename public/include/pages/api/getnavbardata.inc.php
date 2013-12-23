<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the system is enabled
if ($setting->getValue('disable_navbar_api')) {
  echo $api->get_json(array('error' => 'disabled'));
  die();
}

// System load check
if ($load = @sys_getloadavg()) {
  if (isset($config['system']['load']['max']) && $load[0] > $config['system']['load']['max']) {
    header('HTTP/1.1 503 Too busy, try again later');
    die('Server too busy. Please try again later.');
  }
}

// Fetch RPC information
if ($bitcoin->can_connect() === true) {
  $dNetworkHashrate = $bitcoin->getnetworkhashps();
  $dDifficulty = $bitcoin->getdifficulty();
  $iBlock = $bitcoin->getblockcount();
} else {
  $dNetworkHashrate = 0;
  $dDifficulty = 1;
  $iBlock = 0;
}

// Some settings
if ( ! $interval = $setting->getValue('statistics_ajax_data_interval')) $interval = 300;
if ( ! $dPoolHashrateModifier = $setting->getValue('statistics_pool_hashrate_modifier') ) $dPoolHashrateModifier = 1;
if ( ! $dNetworkHashrateModifier = $setting->getValue('statistics_network_hashrate_modifier') ) $dNetworkHashrateModifier = 1;

// Fetch raw data
$statistics->setGetCache(false);
$dPoolHashrate = $statistics->getCurrentHashrate($interval);
if ($dPoolHashrate > $dNetworkHashrate) $dNetworkHashrate = $dPoolHashrate;
$statistics->setGetCache(true);

// Apply pool modifiers
$dPoolHashrateAdjusted = $dPoolHashrate * $dPoolHashrateModifier;
$dNetworkHashrateAdjusted = $dNetworkHashrate / 1000 * $dNetworkHashrateModifier;

// Use caches for this one
$aRoundShares = $statistics->getRoundShares();

$iTotalRoundShares = $aRoundShares['valid'] + $aRoundShares['invalid'];
if ($iTotalRoundShares > 0) {
  $dPoolInvalidPercent = round($aRoundShares['invalid'] / $iTotalRoundShares * 100, 2);
} else {
  $dUserInvalidPercent = 0;
  $dPoolInvalidPercent = 0;
}

// Round progress
$iEstShares = $statistics->getEstimatedShares($dDifficulty);
if ($iEstShares > 0 && $aRoundShares['valid'] > 0) {
    $dEstPercent = round(100 / $iEstShares * $aRoundShares['valid'], 2);
} else {
    $dEstPercent = 0;
}

// Output JSON format
$data = array(
  'raw' => array( 'workers' => $worker->getCountAllActiveWorkers(), 'pool' => array( 'hashrate' => $dPoolHashrate ) ),
  'pool' => array( 'workers' => $worker->getCountAllActiveWorkers(), 'hashrate' => $dPoolHashrateAdjusted, 'estimated' => $iEstShares, 'progress' => $dEstPercent ),
  'network' => array( 'hashrate' => $dNetworkHashrateAdjusted, 'difficulty' => $dDifficulty, 'block' => $iBlock ),
);
echo $api->get_json($data);

// Supress master template
$supress_master = 1;
?>
