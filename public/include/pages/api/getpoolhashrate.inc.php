<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Fetch settings
if ( ! $interval = $setting->getValue('statistics_ajax_data_interval')) $interval = 300;

// Output JSON format
$statistics->setGetCache(false);
$dPoolHashrate = $statistics->getCurrentHashrate($interval);
$statistics->setGetCache(true);

echo $api->get_json($dPoolHashrate);

// Supress master template
$supress_master = 1;
?>
