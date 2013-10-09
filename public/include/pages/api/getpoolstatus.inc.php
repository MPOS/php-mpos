<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Fetch last block information
$aLastBlock = $block->getLast();

// Efficiency
$aShares = $statistics->getRoundShares();
$aShares['valid'] > 0 ? $dEfficiency = round((100 - (100 / $aShares['valid'] * $aShares['invalid'])), 2) : $dEfficiency = 0;

// Fetch RPC data
if ($bitcoin->can_connect() === true){
  $dDifficulty = $bitcoin->getdifficulty();
  $iBlock = $bitcoin->getblockcount();
  $dNetworkHashrate = $bitcoin->getnetworkhashps();
} else {
  $dDifficulty = 1;
  $iBlock = 0;
  $dNetworkHashrate = 0;
}

// Estimated time to find the next block
$iCurrentPoolHashrate =  $statistics->getCurrentHashrate();

// Avoid confusion, ensure our nethash isn't higher than poolhash
if ($iCurrentPoolHashrate > $dNetworkHashrate) $dNetworkHashrate = $iCurrentPoolHashrate;

// Time in seconds, not hours, using modifier in smarty to translate
$iCurrentPoolHashrate > 0 ? $iEstTime = $dDifficulty * pow(2,32) / ($iCurrentPoolHashrate * 1000) : $iEstTime = 0;
$iEstShares = (pow(2, 32 - $config['difficulty']) * $dDifficulty);

// Time since last
$now = new DateTime( "now" );
if (!empty($aLastBlock)) {
    $dTimeSinceLast = ($now->getTimestamp() - $aLastBlock['time']);
} else {
    $dTimeSinceLast = 0;
}

// Output JSON format
$data = array(
  'hashrate' => $iCurrentPoolHashrate,
  'efficiency' => $dEfficiency,
  'workers' => $worker->getCountAllActiveWorkers(),
  'currentnetworkblock' =>  $iBlock,
  'nextnetworkblock' =>  $iBlock + 1,
  'lastblock' => $aLastBlock['height'],
  'networkdiff' => $dDifficulty,
  'esttime' => $iEstTime,
  'estshares' => $iEstShares,
  'timesincelast' => $dTimeSinceLast,
  'nethashrate' => $dNetworkHashrate
);

echo $api->get_json($data);

// Supress master template
$supress_master = 1;
?>
