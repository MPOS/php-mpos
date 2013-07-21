<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$id = $user->checkApiKey($_REQUEST['api_key']);

// Estimated time to find the next block
$iCurrentPoolHashrate = $statistics->getCurrentHashrate() * 1000;

// Output JSON format
echo json_encode(array('getestimatedtime' => $bitcoin->getestimatedtime($iCurrentPoolHashrate)));

// Supress master template
$supress_master = 1;
?>
