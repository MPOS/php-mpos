<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$id = $user->checkApiKey($_REQUEST['api_key']);

// Fetch our last block found
$aBlocksFoundData = $statistics->getBlocksFound(1);

// Time since last block
$now = new DateTime( "now" );
if (!empty($aBlocksFoundData)) {
  $dTimeSinceLast = ($now->getTimestamp() - $aBlocksFoundData[0]['time']);
} else {
  $dTimeSinceLast = 0;
}

// Output JSON format
echo json_encode(array('gettimesincelastblock' => $dTimeSinceLast));

// Supress master template
$supress_master = 1;
?>
