<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Globally available variables
$debug->append('Global smarty variables', 3);

// Defaults to get rid of PHP Notice warnings
$dDifficulty = 1;
$aRoundShares = 1;

// Only run these if the user is logged in
if (@$_SESSION['AUTHENTICATED']) {
  $aRoundShares = $statistics->getRoundShares();
  if ($bitcoin->can_connect() === true) {
    $dDifficulty = $bitcoin->query('getdifficulty');
    if (is_array($dDifficulty) && array_key_exists('proof-of-work', $dDifficulty))
      $dDifficulty = $dDifficulty['proof-of-work'];
  }
}
// Always fetch this since we need for ministats header
$bitcoin->can_connect() === true ? $dNetworkHashrate = $bitcoin->query('getnetworkhashps') : $dNetworkHashrate = 0;

// Fetch some data
$iCurrentActiveWorkers = $worker->getCountAllActiveWorkers();
$iCurrentPoolHashrate =  $statistics->getCurrentHashrate();
$iCurrentPoolShareRate = $statistics->getCurrentShareRate();

// Avoid confusion, ensure our nethash isn't higher than poolhash
if ($iCurrentPoolHashrate > $dNetworkHashrate) $dNetworkHashrate = $iCurrentPoolHashrate;

// Global data for Smarty
$aGlobal = array(
  'slogan' => $config['website']['slogan'],
  'websitename' => $config['website']['name'],
  'hashrate' => $iCurrentPoolHashrate,
  'nethashrate' => $dNetworkHashrate,
  'sharerate' => $iCurrentPoolShareRate,
  'workers' => $iCurrentActiveWorkers,
  'roundshares' => $aRoundShares,
  'fees' => $config['fees'],
  'confirmations' => $config['confirmations'],
  'reward' => $config['reward'],
  'price' => $setting->getValue('price'),
  'blockexplorer' => $config['blockexplorer'],
  'chaininfo' => $config['chaininfo'],
  'config' => array(
    'website' => array( 'title' => $config['website']['title'], 'acl' => $config['website']['acl'] ),
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

// Special calculations for PPS Values based on reward_type setting and/or available blocks
if ($config['reward_type'] != 'block') {
  $aGlobal['ppsvalue'] = number_format(round(50 / (pow(2,32) * $dDifficulty) * pow(2, $config['difficulty']), 12) ,12);
} else {
  // Try to find the last block value and use that for future payouts, revert to fixed reward if none found
  if ($aLastBlock = $block->getLast()) {
    $aGlobal['ppsvalue'] = number_format(round($aLastBlock['amount'] / (pow(2,32) * $dDifficulty) * pow(2, $config['difficulty']), 12) ,12);
  } else {
    $aGlobal['ppsvalue'] = number_format(round($config['reward'] / (pow(2,32) * $dDifficulty) * pow(2, $config['difficulty']), 12) ,12);
  }
}

// We don't want these session infos cached
if (@$_SESSION['USERDATA']['id']) {
  $aGlobal['userdata'] = $_SESSION['USERDATA']['id'] ? $user->getUserData($_SESSION['USERDATA']['id']) : array();
  $aGlobal['userdata']['balance'] = $transaction->getBalance($_SESSION['USERDATA']['id']);

  // Other userdata that we can cache savely
  $aGlobal['userdata']['shares'] = $statistics->getUserShares($_SESSION['USERDATA']['id']);
  $aGlobal['userdata']['hashrate'] = $statistics->getUserHashrate($_SESSION['USERDATA']['id']);
  $aGlobal['userdata']['sharerate'] = $statistics->getUserSharerate($_SESSION['USERDATA']['id']);

  switch ($config['payout_system']) {
  case 'prop' || 'pplns':
    // Some estimations
    if (@$aRoundShares['valid'] > 0) {
      $aGlobal['userdata']['est_block'] = round(( (int)$aGlobal['userdata']['shares']['valid'] / (int)$aRoundShares['valid'] ) * (float)$config['reward'], 8);
      $aGlobal['userdata']['est_fee'] = round(((float)$config['fees'] / 100) * (float)$aGlobal['userdata']['est_block'], 8);
      $aGlobal['userdata']['est_donation'] = round((( (float)$aGlobal['userdata']['donate_percent'] / 100) * ((float)$aGlobal['userdata']['est_block'] - (float)$aGlobal['userdata']['est_fee'])), 8);
      $aGlobal['userdata']['est_payout'] = round((float)$aGlobal['userdata']['est_block'] - (float)$aGlobal['userdata']['est_donation'] - (float)$aGlobal['userdata']['est_fee'], 8);
    } else {
      $aGlobal['userdata']['est_block'] = 0;
      $aGlobal['userdata']['est_fee'] = 0;
      $aGlobal['userdata']['est_donation'] = 0;
      $aGlobal['userdata']['est_payout'] = 0;
    }
  case 'pplns':
    if ($iAvgBlockShares = round($block->getAvgBlockShares($config['pplns']['blockavg']['blockcount']))) {
      $aGlobal['pplns']['target'] = $iAvgBlockShares;
    } else {
      $aGlobal['pplns']['target'] = $config['pplns']['shares']['default'];
    }
    break;
  case 'pps':
    break;
  }

  // Site-wide notifications, based on user events
  if ($aGlobal['userdata']['balance']['confirmed'] >= $config['ap_threshold']['max'])
    $_SESSION['POPUP'][] = array('CONTENT' => 'You have exceeded the pools configured ' . $config['currency'] . ' warning threshold. Please initiate a transfer!', 'TYPE' => 'warning');
  if ($user->getUserFailed($_SESSION['USERDATA']['id']) > 0)
    $_SESSION['POPUP'][] = array('CONTENT' => 'You have ' . $user->getUserFailed($_SESSION['USERDATA']['id']) . ' failed login attempts! <a href="?page=account&action=reset_failed">Reset Counter</a>', 'TYPE' => 'errormsg');
}

if ($setting->getValue('maintenance'))
  $_SESSION['POPUP'][] = array('CONTENT' => 'This pool is currently in maintenance mode.', 'TYPE' => 'warning');

// Make it available in Smarty
$smarty->assign('PATH', 'site_assets/' . THEME);
$smarty->assign('GLOBAL', $aGlobal);
?>
