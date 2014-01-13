<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

$blocks = $statistics->getLastBlocksbyTime();

// Output JSON format
echo $api->get_json($blocks); 

// Supress master template
$supress_master = 1;
?>
