<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Fetch our last block found
$aBlocksFoundData = $statistics->getBlocksFound(1);

// Time since last block
$now = new DateTime( "now" );
! empty($aBlocksFoundData) ? $dTimeSinceLast = ($now->getTimestamp() - $aBlocksFoundData[0]['time']) : $dTimeSinceLast = 0;

// Output JSON format
echo $api->get_json($dTimeSinceLast);

// Supress master template
$supress_master = 1;
?>
