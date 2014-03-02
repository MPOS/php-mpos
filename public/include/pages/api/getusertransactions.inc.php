<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Fetch transactions
if (isset($_REQUEST['limit']) && $_REQUEST['limit'] <= 100) {
  $limit = $_REQUEST['limit'];
} else {
  // Force limit
  $limit = 100;
}
$data['transactions'] = $transaction->getTransactions(0, NULL, $limit, $user_id);

// Fetch summary if enabled
if (!$setting->getValue('disable_transactionsummary')) {
  $aTransactionSummary = $transaction->getTransactionSummary($user_id);
  $data['transactionsummary'] = $aTransactionSummary;
}

// Output JSON format
echo $api->get_json($data);

// Supress master template
$supress_master = 1;
?>
