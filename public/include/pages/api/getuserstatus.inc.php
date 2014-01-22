<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);
$username = $user->getUsername($user_id);
// Fetch transaction summary
$aTransactionSummary = $transaction->getTransactionSummary($user_id);

// Output JSON format
$data = array(
  'username' => $username,
  'shares' =>  $statistics->getUserShares($username, $user_id),
  'hashrate' => $statistics->getUserHashrate($username, $user_id),
  'sharerate' => $statistics->getUserSharerate($username, $user_id)
);
echo $api->get_json($data);

// Supress master template
$supress_master = 1;
?>
