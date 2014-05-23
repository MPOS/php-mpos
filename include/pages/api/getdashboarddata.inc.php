<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the system is enabled
if ($setting->getValue('disable_dashboard_api')) {
  echo $api->get_json(array('error' => 'disabled'));
  die();
}

// System load check
if ($load = @sys_getloadavg()) {
  if (isset($config['system']['load']['max']) && $load[0] > $config['system']['load']['max']) {
    header('HTTP/1.1 503 Too busy, try again later');
    die('Server too busy. Please try again later.');
  }
}

// Supress master template
$supress_master = 1;

// Check user token and access level permissions
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);
$username = $user->getUsername($user_id);

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
$aUserMiningStats = $statistics->getUserMiningStats($username, $user_id, $interval);
$dPersonalHashrate = $aUserMiningStats['hashrate'];
$dPersonalSharerate = $aUserMiningStats['sharerate'];
$dPersonalShareDifficulty = $aUserMiningStats['avgsharediff'];
$statistics->setGetCache(true);

// Use caches for this one
$aUserRoundShares = $statistics->getUserShares($username, $user_id);
$aRoundShares = $statistics->getRoundShares();

if ($config['payout_system'] != 'pps') {
  $aEstimates = $statistics->getUserEstimates($aRoundShares, $aUserRoundShares, $user->getUserDonatePercent($user_id), $user->getUserNoFee($user_id));
  $dUnpaidShares = 0;
} else {
  $dUnpaidShares = $statistics->getUserUnpaidPPSShares($username, $user_id, $setting->getValue('pps_last_share_id'));
  $aEstimates = $statistics->getUserEstimates($dPersonalSharerate, $dPersonalShareDifficulty, $user->getUserDonatePercent($user_id), $user->getUserNoFee($user_id), $statistics->getPPSValue());
}

// Round/user percentages
$aRoundShares['valid'] + $aRoundShares['invalid'] > 0 ? $dPoolInvalidPercent = round($aRoundShares['invalid'] / ($aRoundShares['valid'] + $aRoundShares['invalid']) * 100, 2) : $dPoolInvalidPercent = 0;
$aUserRoundShares['valid'] + $aUserRoundShares['valid'] > 0 ? $dUserInvalidPercent = round($aUserRoundShares['invalid'] / ($aUserRoundShares['valid'] + $aUserRoundShares['invalid']) * 100, 2) : $dUserInvalidPercent = 0;

// Apply pool modifiers
$dPersonalHashrateAdjusted = $dPersonalHashrate * $dPersonalHashrateModifier;
$dPoolHashrateAdjusted = $dPoolHashrate * $dPoolHashrateModifier;
$dNetworkHashrateAdjusted = $dNetworkHashrate / 1000 * $dNetworkHashrateModifier;

// Coin price
$aPrice = $setting->getValue('price');

// Round progress
$iEstShares = $statistics->getEstimatedShares($dDifficulty);
$iEstShares > 0 && $aRoundShares['valid'] > 0 ? $dEstPercent = round(100 / $iEstShares * $aRoundShares['valid'], 2) : $dEstPercent = 0;

// Some estimates
$dExpectedTimePerBlock = $statistics->getExpectedTimePerBlock();
$dPoolExpectedTimePerBlock = $statistics->getExpectedTimePerBlock('pool', $dPoolHashrate);
$dEstNextDifficulty = $statistics->getExpectedNextDifficulty();
$iBlocksUntilDiffChange = $statistics->getBlocksUntilDiffChange();

// Block statistics
$aLastBlocks = $statistics->getBlocksFound(5);
if (!$user->isAdmin(@$_SESSION['USERDATA']['id'])) {
  foreach ($aLastBlocks as $key => $data) {
    if ($data['is_anonymous'] == 1) {
      $aLastBlocks[$key]['worker_name'] = 'anonymous';
      $aLastBlocks[$key]['finder'] = 'anonymous';
    }
  }
}

// Output JSON format
$data = array(
  'raw' => array( 'personal' => array( 'hashrate' => $dPersonalHashrate ), 'pool' => array( 'hashrate' => $dPoolHashrate ), 'network' => array( 'hashrate' => $dNetworkHashrate / 1000, 'esttimeperblock' => $dExpectedTimePerBlock, 'nextdifficulty' => $dEstNextDifficulty, 'blocksuntildiffchange' => $iBlocksUntilDiffChange ) ),
  'personal' => array (
    'hashrate' => $dPersonalHashrateAdjusted, 'sharerate' => $dPersonalSharerate, 'sharedifficulty' => $dPersonalShareDifficulty,
    'shares' => array('valid' => $aUserRoundShares['valid'], 'invalid' => $aUserRoundShares['invalid'], 'invalid_percent' => $dUserInvalidPercent, 'unpaid' => $dUnpaidShares ),
    'estimates' => $aEstimates),
  'pool' => array(
    'info' => array(
      'name' => $setting->getValue('website_name'),
      'currency' => $config['currency']
    ),
    'esttimeperblock' => round($dPoolExpectedTimePerBlock, 2),
    'blocks' => $aLastBlocks,
    'workers' => $worker->getCountAllActiveWorkers(), 'hashrate' => $dPoolHashrateAdjusted,
    'shares' => array( 'valid' => $aRoundShares['valid'], 'invalid' => $aRoundShares['invalid'], 'invalid_percent' => $dPoolInvalidPercent, 'estimated' => $iEstShares, 'progress' => $dEstPercent ),
    'price' => $aPrice,
    'difficulty' => pow(2, $config['difficulty'] - 16),
    'target_bits' => $coin->getTargetBits()
  ),
  'system' => array( 'load' => sys_getloadavg() ),
  'network' => array( 'hashrate' => $dNetworkHashrateAdjusted, 'difficulty' => $dDifficulty, 'block' => $iBlock, 'esttimeperblock' => round($dExpectedTimePerBlock ,2), 'nextdifficulty' => $dEstNextDifficulty, 'blocksuntildiffchange' => $iBlocksUntilDiffChange ),
);

echo $api->get_json($data);
?>
