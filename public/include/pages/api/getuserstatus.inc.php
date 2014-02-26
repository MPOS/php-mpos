<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);
$username = $user->getUsername($user_id);

// Fetch some settings
if ( ! $interval = $setting->getValue('statistics_ajax_data_interval')) $interval = 300;

// Fetch transaction summary
$aTransactionSummary = $transaction->getTransactionSummary($user_id);

// User mining status
$aUserMiningStats = $statistics->getUserMiningStats($username, $user_id, $interval);

// Output JSON format
$data = array(
  'username' => $username,
  'shares' =>  $statistics->getUserShares($username, $user_id),
  'hashrate' => $aUserMiningStats['hashrate'],
  'sharerate' => $aUserMiningStats['sharerate']
);
echo $api->get_json($data);

// Supress master template
$supress_master = 1;
?>
