<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

if (!$user->checkApiKey($_REQUEST['api_key'])) {
  header("HTTP/1.1 401 Unauthorized");
  die();
}

// Fetch data from litecoind
if ($bitcoin->can_connect() === true){
  if (!$dDifficulty = $memcache->get('dDifficulty')) {
    $dDifficulty = $bitcoin->query('getdifficulty');
    $memcache->set('dDifficulty', $dDifficulty);
  }
  if (!$iBlock = $memcache->get('iBlock')) {
    $iBlock = $bitcoin->query('getblockcount');
    $memcache->set('iBlock', $iBlock);
  }
} else {
  $iDifficulty = 1;
  $iBlock = 0;
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to pushpool service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
}

// Grab the last 10 blocks found
$iLimit = 10;
$aBlocksFoundData = $statistics->getBlocksFound($iLimit);
$aBlockData = $aBlocksFoundData[0];

// Estimated time to find the next block
$iCurrentPoolHashrate =  $statistics->getCurrentHashrate();
// Time in seconds, not hours, using modifier in smarty to translate
$iEstTime = $dDifficulty * pow(2,32) / ($iCurrentPoolHashrate * 1000);

// Time since last block
$now = new DateTime( "now" );
if (!empty($aBlockData)) {
  $dTimeSinceLast = ($now->getTimestamp() - $aBlockData['time']);
} else {
  $dTimeSinceLast = 0;
}

$aData = array(
  'est_time' => $iEstTime,
  'time_last' => $dTimeSinceLast,
  'blocks_found' => $aBlocksFoundData,
  'cur_block' => $iBlock,
  'last_block' => $aBlockData['height'],
  'difficulty' => $iDifficulty,
);

$supress_master = 1;
echo json_encode($aData);
?>
