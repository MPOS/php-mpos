<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $user->checkApiKey($_REQUEST['api_key']);

// Fetch last block information
$aLastBlock = $block->getLast();

// Efficiency
$aShares = $statistics->getRoundShares();
$aShares['valid'] > 0 ? $dEfficiency = round((100 - (100 / $aShares['valid'] * $aShares['invalid'])), 2) : $dEfficiency = 0;

// Fetch RPC data
if ($bitcoin->can_connect() === true){
  $dDifficulty = $bitcoin->getdifficulty();
  if (is_array($dDifficulty) && array_key_exists('proof-of-work', $dDifficulty))
    $dDifficulty = $dDifficulty['proof-of-work'];
  $iBlock = $bitcoin->getblockcount();
} else {
  $dDifficulty = 1;
  $iBlock = 0;
}

// Estimated time to find the next block
$iCurrentPoolHashrate =  $statistics->getCurrentHashrate();

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
echo json_encode(
  array(
    'getpoolstatus' => array(
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
    )));

// Supress master template
$supress_master = 1;
?>
