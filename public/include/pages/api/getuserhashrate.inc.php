<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);
$username = $user->getUsername($user_id);

// Fetch some settings
if ( ! $interval = $setting->getValue('statistics_ajax_data_interval')) $interval = 300;

// Gather un-cached data
$statistics->setGetCache(false);
$aUserMiningStats = $statistics->getUserMiningStats($username, $user_id, $interval);
$hashrate = $aUserMiningStats['hashrate'];
$statistics->setGetCache(true);

// Output JSON
echo $api->get_json($hashrate);

// Supress master template
$supress_master = 1;
?>
