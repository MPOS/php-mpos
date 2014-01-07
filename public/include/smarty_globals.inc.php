<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Globally available variables
$debug->append('Global smarty variables', 3);

$debug->append('No cached page detected, loading smarty globals', 3);
// Defaults to get rid of PHP Notice warnings
$dDifficulty = 1;

// Fetch round shares
if (!$aRoundShares = $statistics->getRoundShares()) {
  $aRoundShares = array('valid' => 0, 'invalid' => 0);
}

if ($bitcoin->can_connect() === true) {
  $dDifficulty = $bitcoin->getdifficulty();
  $dNetworkHashrate = $bitcoin->getnetworkhashps();
} else {
  $dDifficulty = 1;
  $dNetworkHashrate = 0;
}

// Baseline pool hashrate for templates
if ( ! $dPoolHashrateModifier = $setting->getValue('statistics_pool_hashrate_modifier') ) $dPoolHashrateModifier = 1;
$iCurrentPoolHashrate =  $statistics->getCurrentHashrate();

// Avoid confusion, ensure our nethash isn't higher than poolhash
if ($iCurrentPoolHashrate > $dNetworkHashrate) $dNetworkHashrate = $iCurrentPoolHashrate;

// Baseline network hashrate for templates
if ( ! $dPersonalHashrateModifier = $setting->getValue('statistics_personal_hashrate_modifier') ) $dPersonalHashrateModifier = 1;
if ( ! $dNetworkHashrateModifier = $setting->getValue('statistics_network_hashrate_modifier') ) $dNetworkHashrateModifier = 1;

// Apply modifier now
$dNetworkHashrate = $dNetworkHashrate / 1000 * $dNetworkHashrateModifier;
$iCurrentPoolHashrate = $iCurrentPoolHashrate * $dPoolHashrateModifier;

// Share rate of the entire pool
$iCurrentPoolShareRate = $statistics->getCurrentShareRate();

// Active workers
if (!$iCurrentActiveWorkers = $worker->getCountAllActiveWorkers()) $iCurrentActiveWorkers = 0;

// Some settings to propagate to template
if (! $statistics_ajax_refresh_interval = $setting->getValue('statistics_ajax_refresh_interval')) $statistics_ajax_refresh_interval = 10;
if (! $statistics_ajax_long_refresh_interval = $setting->getValue('statistics_ajax_long_refresh_interval')) $statistics_ajax_long_refresh_interval = 10;

// Small helper array
$aHashunits = array( '1' => 'KH/s', '0.001' => 'MH/s', '0.000001' => 'GH/s' );

// Global data for Smarty
$aGlobal = array(
  'hashunits' => array( 'pool' => $aHashunits[$dPoolHashrateModifier], 'network' => $aHashunits[$dNetworkHashrateModifier], 'personal' => $aHashunits[$dPersonalHashrateModifier]),
  'hashmods' => array( 'personal' => $dPersonalHashrateModifier ),
  'hashrate' => $iCurrentPoolHashrate,
  'nethashrate' => $dNetworkHashrate,
  'sharerate' => $iCurrentPoolShareRate,
  'workers' => $iCurrentActiveWorkers,
  'roundshares' => $aRoundShares,
  'fees' => $config['fees'],
  'confirmations' => $config['confirmations'],
  'reward' => $config['reward'],
  'price' => $setting->getValue('price'),
  'config' => array(
    'disable_navbar' => $setting->getValue('disable_navbar'),
    'disable_navbar_api' => $setting->getValue('disable_navbar_api'),
    'disable_payouts' => $setting->getValue('disable_payouts'),
    'disable_manual_payouts' => $setting->getValue('disable_manual_payouts'),
    'disable_auto_payouts' => $setting->getValue('disable_auto_payouts'),
    'disable_contactform' => $setting->getValue('disable_contactform'),
    'disable_contactform_guest' => $setting->getValue('disable_contactform_guest'),
    'algorithm' => $config['algorithm'],
    'target_bits' => $config['target_bits'],
    'accounts' => $config['accounts'],
    'disable_invitations' => $setting->getValue('disable_invitations'),
    'disable_notifications' => $setting->getValue('disable_notifications'),
    'monitoring_uptimerobot_api_keys' => $setting->getValue('monitoring_uptimerobot_api_keys'),
    'statistics_ajax_refresh_interval' => $statistics_ajax_refresh_interval,
    'statistics_ajax_long_refresh_interval' => $statistics_ajax_long_refresh_interval,
    'price' => array( 'currency' => $config['price']['currency'] ),
    'targetdiff' => $config['difficulty'],
    'currency' => $config['currency'],
    'txfee' => $config['txfee'],
    'payout_system' => $config['payout_system'],
    'ap_threshold' => array(
      'min' => $config['ap_threshold']['min'],
      'max' => $config['ap_threshold']['max']
    )
  )
);

// Website configurations
$aGlobal['website']['name'] = $setting->getValue('website_name');
$aGlobal['website']['title'] = $setting->getValue('website_title');
$aGlobal['website']['slogan'] = $setting->getValue('website_slogan');
$aGlobal['website']['email'] = $setting->getValue('website_email');
$aGlobal['website']['api']['disabled'] = $setting->getValue('disable_api');
$aGlobal['website']['blockexplorer']['disabled'] = $setting->getValue('website_blockexplorer_disabled');
$aGlobal['website']['transactionexplorer']['disabled'] = $setting->getValue('website_transactionexplorer_disabled');
$aGlobal['website']['chaininfo']['disabled'] = $setting->getValue('website_chaininfo_disabled');
$aGlobal['website']['donors']['disabled'] = $setting->getValue('disable_donors');
$aGlobal['website']['about']['disabled'] = $setting->getValue('disable_about');
$setting->getValue('website_blockexplorer_url') ? $aGlobal['website']['blockexplorer']['url'] = $setting->getValue('website_blockexplorer_url') : $aGlobal['website']['blockexplorer']['url'] = 'http://explorer.litecoin.net/block/';
$setting->getValue('website_transactionexplorer_url') ? $aGlobal['website']['transactionexplorer']['url'] = $setting->getValue('website_transactionexplorer_url') : $aGlobal['website']['transactionexplorer']['url'] = 'http://explorer.litecoin.net/tx/';
$setting->getValue('website_chaininfo_url') ? $aGlobal['website']['chaininfo']['url'] = $setting->getValue('website_chaininfo_url') : $aGlobal['website']['chaininfo']['url'] = 'http://allchains.info';

// Google Analytics
$aGlobal['statistics']['analytics']['enabled'] = $setting->getValue('statistics_analytics_enabled');
$aGlobal['statistics']['analytics']['code'] = $setting->getValue('statistics_analytics_code');

// ACLs
$aGlobal['acl']['pool']['statistics'] = $setting->getValue('acl_pool_statistics');
$aGlobal['acl']['block']['statistics'] = $setting->getValue('acl_block_statistics');
$aGlobal['acl']['round']['statistics'] = $setting->getValue('acl_round_statistics');
$aGlobal['acl']['blockfinder']['statistics'] = $setting->getValue('acl_blockfinder_statistics');
$aGlobal['acl']['uptime']['statistics'] = $setting->getValue('acl_uptime_statistics');

// We don't want these session infos cached
if (@$_SESSION['USERDATA']['id']) {
  $aGlobal['userdata'] = $_SESSION['USERDATA']['id'] ? $user->getUserData($_SESSION['USERDATA']['id']) : array();
  $aGlobal['userdata']['balance'] = $transaction->getBalance($_SESSION['USERDATA']['id']);

  // Other userdata that we can cache savely
  $aGlobal['userdata']['shares'] = $statistics->getUserShares($_SESSION['USERDATA']['id']);
  $aGlobal['userdata']['rawhashrate'] = $statistics->getUserHashrate($_SESSION['USERDATA']['id']);
  $aGlobal['userdata']['hashrate'] = $aGlobal['userdata']['rawhashrate'] * $dPersonalHashrateModifier;
  $aGlobal['userdata']['sharerate'] = $statistics->getUserSharerate($_SESSION['USERDATA']['id']);

  switch ($config['payout_system']) {
  case 'prop':
    // Some estimations
    $aEstimates = $statistics->getUserEstimates($aRoundShares, $aGlobal['userdata']['shares'], $aGlobal['userdata']['donate_percent'], $aGlobal['userdata']['no_fees']);
    $aGlobal['userdata']['estimates'] = $aEstimates;
    break;
  case 'pplns':
    $aGlobal['pplns']['target'] = $config['pplns']['shares']['default'];
    if ($aLastBlock = $block->getLast()) {
      if ($iAvgBlockShares = round($block->getAvgBlockShares($aLastBlock['height'], $config['pplns']['blockavg']['blockcount']))) {
        $aGlobal['pplns']['target'] = $iAvgBlockShares;
      }
    }
    $aEstimates = $statistics->getUserEstimates($aRoundShares, $aGlobal['userdata']['shares'], $aGlobal['userdata']['donate_percent'], $aGlobal['userdata']['no_fees']);
    $aGlobal['userdata']['estimates'] = $aEstimates;
    break;
  case 'pps':
    $aGlobal['userdata']['pps']['unpaidshares'] = $statistics->getUserUnpaidPPSShares($_SESSION['USERDATA']['id'], $setting->getValue('pps_last_share_id'));
    $aGlobal['ppsvalue'] = number_format($statistics->getPPSValue(), 12);
    $aGlobal['poolppsvalue'] = $aGlobal['ppsvalue'] * pow(2, $config['difficulty'] - 16);
    $aGlobal['userdata']['sharedifficulty'] = $statistics->getUserShareDifficulty($_SESSION['USERDATA']['id']);
    $aGlobal['userdata']['estimates'] = $statistics->getUserEstimates($aGlobal['userdata']['sharerate'], $aGlobal['userdata']['sharedifficulty'], $aGlobal['userdata']['donate_percent'], $aGlobal['userdata']['no_fees'], $aGlobal['ppsvalue']);
    break;
  }

  // Site-wide notifications, based on user events
  if ($aGlobal['userdata']['balance']['confirmed'] >= $config['ap_threshold']['max'])
    $_SESSION['POPUP'][] = array('CONTENT' => 'You have exceeded the pools configured ' . $config['currency'] . ' warning threshold. Please initiate a transfer!', 'TYPE' => 'errormsg');
  if ($user->getUserFailed($_SESSION['USERDATA']['id']) > 0)
    $_SESSION['POPUP'][] = array('CONTENT' => 'You have ' . $user->getUserFailed($_SESSION['USERDATA']['id']) . ' failed login attempts! <a href="?page=account&action=reset_failed">Reset Counter</a>', 'TYPE' => 'errormsg');
}

if ($setting->getValue('maintenance'))
  $_SESSION['POPUP'][] = array('CONTENT' => 'This pool is currently in maintenance mode.', 'TYPE' => 'warning');
if ($motd = $setting->getValue('system_motd'))
  $_SESSION['POPUP'][] = array('CONTENT' => $motd, 'TYPE' => 'info');

// So we can display additional info
$smarty->assign('DEBUG', DEBUG);

// Make it available in Smarty
$smarty->assign('PATH', 'site_assets/' . THEME);
$smarty->assign('GLOBALASSETS', 'site_assets/global');
$smarty->assign('GLOBAL', $aGlobal);
?>
