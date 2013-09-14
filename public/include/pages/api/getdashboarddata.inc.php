<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $user->checkApiKey($_REQUEST['api_key']);

/**
 * This check will ensure the user can do the following:
 * Admin: Check any user via request id
 * Regular: Check your own status
 * Other: Deny access via checkApiKey
 **/
if ( ! $user->isAdmin($user_id) && ($_REQUEST['id'] != $user_id && !empty($_REQUEST['id']))) {
  // User is admin and tries to access an ID that is not their own
  header("HTTP/1.1 401 Unauthorized");
  die("Access denied");
} else if ($user->isAdmin($user_id)) {
  // Admin, so allow any ID passed in request
  $id = $_REQUEST['id'];
  // Is it a username or a user ID
  ctype_digit($_REQUEST['id']) ? $username = $user->getUserName($_REQUEST['id']) : $username = $_REQUEST['id'];
  ctype_digit($_REQUEST['id']) ? $id = $_REQUEST['id'] : $id = $user->getUserId($_REQUEST['id']);
} else {
  // Not admin, only allow own user ID
  $id = $user_id;
  $username = $user->getUserName($id);
}

// Fetch raw RPC data
$bitcoin->can_connect() === true ? $dNetworkHashrate = $bitcoin->query('getnetworkhashps') : $dNetworkHashrate = 0;

// Some settings
$start = microtime(true);
if ( ! $interval = $setting->getValue('statistics_ajax_data_interval')) $interval = 300;
if ( ! $dPoolHashrateModifier = $setting->getValue('statistics_pool_hashrate_modifier') ) $dPoolHashrateModifier = 1;
if ( ! $dPersonalHashrateModifier = $setting->getValue('statistics_personal_hashrate_modifier') ) $dPersonalHashrateModifier = 1;
if ( ! $dNetworkHashrateModifier = $setting->getValue('statistics_network_hashrate_modifier') ) $dNetworkHashrateModifier = 1;

// Fetch raw data
$statistics->setGetCache(false);
$dPoolHashrate = $statistics->getCurrentHashrate($interval);
if ($dPoolHashrate > $dNetworkHashrate) $dNetworkHashrate = $dPoolHashrate;
$dPersonalHashrate = $statistics->getUserHashrate($id, $interval);
$dPersonalSharerate = $statistics->getUserSharerate($id, $interval);
$statistics->setGetCache(true);

$runtime = (microtime(true) - $start) * 1000;

// Apply pool modifiers
$dPersonalHashrate = $dPersonalHashrate * $dPersonalHashrateModifier;
$dPoolHashrate = $dPoolHashrate * $dPoolHashrateModifier;
$dNetworkHashrate = $dNetworkHashrate / 1000 * $dNetworkHashrateModifier;

// Output JSON format
echo json_encode(array($_REQUEST['action'] => array(
  'datatime' => $runtime,
  'personal' => array ( 'hashrate' => $dPersonalHashrate, 'sharerate' => $dPersonalSharerate ),
  'pool' => array( 'hashrate' => $dPoolHashrate ),
  'network' => array( 'hashrate' => $dNetworkHashrate ),
)));

// Supress master template
$supress_master = 1;
?>
