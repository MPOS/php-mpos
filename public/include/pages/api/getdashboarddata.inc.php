<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token and access level permissions
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Fetch RPC information
if ($bitcoin->can_connect() === true) {
  $dNetworkHashrate = $bitcoin->getnetworkhashps();
  $dDifficulty = $bitcoin->getdifficulty();
  $iBlock = $bitcoin->getblockcount();
} else {
  $dNetworkHashrate = 0;
  $dDifficulty = 1;
  $iBlock = 0;
}

// Some settings
if ( ! $interval = $setting->getValue('statistics_ajax_data_interval')) $interval = 300;
if ( ! $dPoolHashrateModifier = $setting->getValue('statistics_pool_hashrate_modifier') ) $dPoolHashrateModifier = 1;
if ( ! $dPersonalHashrateModifier = $setting->getValue('statistics_personal_hashrate_modifier') ) $dPersonalHashrateModifier = 1;
if ( ! $dNetworkHashrateModifier = $setting->getValue('statistics_network_hashrate_modifier') ) $dNetworkHashrateModifier = 1;

// Fetch raw data
$statistics->setGetCache(false);
$dPoolHashrate = $statistics->getCurrentHashrate($interval);
if ($dPoolHashrate > $dNetworkHashrate) $dNetworkHashrate = $dPoolHashrate;
$dPersonalHashrate = $statistics->getUserHashrate($user_id, $interval);
$dPersonalSharerate = $statistics->getUserSharerate($user_id, $interval);
$statistics->setGetCache(true);

// Use caches for this one
$aUserRoundShares = $statistics->getUserShares($user_id);
$aRoundShares = $statistics->getRoundShares();

// Apply pool modifiers
$dPersonalHashrateAdjusted = $dPersonalHashrate * $dPersonalHashrateModifier;
$dPoolHashrateAdjusted = $dPoolHashrate * $dPoolHashrateModifier;
$dNetworkHashrateAdjusted = $dNetworkHashrate / 1000 * $dNetworkHashrateModifier;

// Output JSON format
$data = array(
  'raw' => array( 'personal' => array( 'hashrate' => $dPersonalHashrate ), 'pool' => array( 'hashrate' => $dPoolHashrate ), 'network' => array( 'hashrate' => $dNetworkHashrate / 1000 ) ),
  'personal' => array ( 'hashrate' => $dPersonalHashrateAdjusted, 'sharerate' => $dPersonalSharerate, 'shares' => $aUserRoundShares, 'balance' => $transaction->getBalance($user_id)),
  'pool' => array( 'hashrate' => $dPoolHashrateAdjusted, 'shares' => $aRoundShares ),
  'network' => array( 'hashrate' => $dNetworkHashrateAdjusted, 'difficulty' => $dDifficulty, 'block' => $iBlock ),
);
echo $api->get_json($data);

// Supress master template
$supress_master = 1;
?>
