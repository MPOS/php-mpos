<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Fetch transactions
if (isset($_REQUEST['limit']) && $_REQUEST['limit'] < 30) {
  $limit = $_REQUEST['limit'];
} else {
  // Force limit
  $limit = 5;
}
$data['transactions'] = $transaction->getTransactions($user_id, NULL, $limit);

// Fetch summary if enabled
if (!$setting->getValue('disable_transactionsummary')) {
  $aTransactionSummary = $transaction->getTransactionSummary($_SESSION['USERDATA']['id']);
  $data['transactionsummary'] = $aTransactionSummary;
}

// Output JSON format
echo $api->get_json($data);

// Supress master template
$supress_master = 1;
?>
