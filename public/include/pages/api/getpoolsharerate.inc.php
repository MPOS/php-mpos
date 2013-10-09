<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Fetch settings
if ( ! $interval = $setting->getValue('statistics_ajax_data_interval')) $interval = 300;

// Output JSON format
echo json_encode(array('getpoolsharerate' => $statistics->getCurrentShareRate()));

// Supress master template
$supress_master = 1;
?>
