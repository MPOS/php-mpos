<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Fetch transactions
if (isset($_REQUEST['start'])) {
  $start = $_REQUEST['start'];
} else {
  // start at the beginning
  $start = 0;
}
if (isset($_REQUEST['limit']) && $_REQUEST['limit'] <= 100) {
  $limit = $_REQUEST['limit'];
} else {
  // Force limit
  $limit = 100;
}
if (isset($_REQUEST['filter']) && is_array($_REQUEST['filter'])) {
  $filter = $_REQUEST['filter'];
} else {
  $filter = NULL;
}

$data['transactions'] = $transaction->getTransactions($start, $filter, $limit, $user_id);

// Fetch summary if enabled
if (!$setting->getValue('disable_transactionsummary')) {
  $aTransactionSummary = $transaction->getTransactionSummary($user_id);
  $data['transactionsummary'] = $aTransactionSummary;
}

// Output JSON format
echo $api->get_json($data);

// Supress master template
$supress_master = 1;
