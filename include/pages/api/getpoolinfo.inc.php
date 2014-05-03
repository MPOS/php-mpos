<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Output JSON format
$data = array(
  // coin info
  'currency'             => $config['currency'],
  'coinname'             => $config['gettingstarted']['coinname'],
  // coin algorithm info
  'cointarget'           => $config['cointarget'],
  'coindiffchangetarget' => $config['coindiffchangetarget'],
  'algorithm'            => $config['algorithm'],
  // stratum
  'stratumport'          => $config['gettingstarted']['stratumport'],
  // payments
  'payout_system'        => $config['payout_system'],
  'confirmations'        => $config['confirmations'],
  'min_ap_threshold'     => $config['ap_threshold']['min'],
  'max_ap_threshold'     => $config['ap_threshold']['max'],
  'reward_type'          => $config['payout_system'] == 'pps' ? $config['pps']['reward']['type'] : $config['reward_type'],
  'reward'               => $config['payout_system'] == 'pps' ? $config['pps']['reward']['default'] : $config['reward'],	
  // fees
  'txfee'                => $config['txfee_manual'], // make it backwards compatible
  'txfee_manual'         => $config['txfee_manual'],
  'txfee_auto'           => $config['txfee_auto'],
  'fees'                 => $config['fees']
);

echo $api->get_json($data);

// Supress master template
$supress_master = 1;
?>
