<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Output JSON format

// Fetch Cached Data
$transaction->setGetCache(true);
echo $api->get_json($transaction->getBalance($user_id));
// Turn Cache off
$transaction->setGetCache(false);

// Supress master template
$supress_master = 1;
?>