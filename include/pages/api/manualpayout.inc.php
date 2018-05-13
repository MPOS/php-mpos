<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);
$username = $user->getUsername($user_id);

// Check PIN
if (!$user->checkPin($user_id, @$_REQUEST['pin'])){
  echo $api->get_json(array('error' => 'true', 'message' => 'Invalid PIN. ' . ($config['maxfailed']['pin'] - $user->getUserPinFailed($user_id)) . ' attempts remaining.'));
  break;
}

// Get balance
$aBalance = $transaction->getBalance($user_id);
$dBalance = $aBalance['confirmed'];

// Process payout
if ($setting->getValue('disable_payouts') == 1 || $setting->getValue('disable_manual_payouts') == 1) {
  $data = array('error' => 'true', 'message' => 'Manual payouts are disabled.');
} else if ($aBalance['confirmed'] < $config['mp_threshold']) {
  $data = array('error' => 'true', 'message' => 'Account balance must be >= ' . $config['mp_threshold'] . ' ' . $config['currency'] . ' to do a Manual Payout.');
} else if (!$coin_address->getCoinAddress($user_id)) {
  $data = array('error' => 'true', 'message' => 'You have no payout address set.');
} else {
  $user->log->log("info", $username." requesting manual payout");
  if ($dBalance > $config['txfee_manual']) {
    if (!$oPayout->isPayoutActive($user_id)) {
      if ($iPayoutId = $oPayout->createPayout($user_id, 0, true)) {
        $data = array('error' => 'false', 'message' => 'Created new manual payout request with ID #' . $iPayoutId);
      } else {
        $data = array('error' => 'true', 'message' => $iPayoutId->getError());
      }
    } else {
      $data = array('error' => 'true', 'message' => 'You already have one active manual payout request.');
    }
  } else {
    $data = array('error' => 'true', 'message' => 'Insufficient funds, you need more than ' . $config['txfee_manual'] . ' ' . $config['currency'] . ' to cover transaction fees');
  }
}

// Output JSON format
echo $api->get_json($data);

// Supress master template
$supress_master = 1;
