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

// Fetch some data
$iCurrentActiveWorkers = $worker->getCountAllActiveWorkers();
$iCurrentPoolHashrate =  $statistics->getCurrentHashrate();
$iCurrentPoolShareRate = $statistics->getCurrentShareRate();

// Global data for Smarty
$aGlobal = array(
  'slogan' => $config['website']['slogan'],
  'websitename' => $config['website']['name'],
  'hashrate' => $iCurrentPoolHashrate,
  'sharerate' => $iCurrentPoolShareRate,
  'ppsvalue' => number_format(round(50 / (pow(2,32) * $dDifficulty) * pow(2, $config['difficulty']), 12) ,12),
  'workers' => $iCurrentActiveWorkers,
  'roundshares' => $aRoundShares,
  'fees' => $config['fees'],
  'confirmations' => $config['confirmations'],
  'reward' => $config['reward'],
  'price' => $setting->getValue('price'),
  'blockexplorer' => $config['blockexplorer'],
  'chaininfo' => $config['chaininfo'],
  'config' => array(
    'website' => array( 'title' => $config['website']['title'] ),
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

// We don't want these session infos cached
if (@$_SESSION['USERDATA']['id']) {
  $aGlobal['userdata'] = $_SESSION['USERDATA']['id'] ? $user->getUserData($_SESSION['USERDATA']['id']) : array();
  $aGlobal['userdata']['balance'] = $transaction->getBalance($_SESSION['USERDATA']['id']);

  // Other userdata that we can cache savely
  $aGlobal['userdata']['shares'] = $statistics->getUserShares($_SESSION['USERDATA']['id']);
  $aGlobal['userdata']['hashrate'] = $statistics->getUserHashrate($_SESSION['USERDATA']['id']);
  $aGlobal['userdata']['sharerate'] = $statistics->getUserSharerate($_SESSION['USERDATA']['id']);

  switch ($config['payout_system']) {
  case 'pps':
    break;
  default:
    // Some estimations
    if (@$aRoundShares['valid'] > 0) {
      $aGlobal['userdata']['est_block'] = round(( (int)$aGlobal['userdata']['shares']['valid'] / (int)$aRoundShares['valid'] ) * (int)$config['reward'], 3);
      $aGlobal['userdata']['est_fee'] = round(($config['fees'] / 100) * $aGlobal['userdata']['est_block'], 3);
      $aGlobal['userdata']['est_donation'] = round((( $aGlobal['userdata']['donate_percent'] / 100) * ($aGlobal['userdata']['est_block'] - $aGlobal['userdata']['est_fee'])), 3);
      $aGlobal['userdata']['est_payout'] = round($aGlobal['userdata']['est_block'] - $aGlobal['userdata']['est_donation'] - $aGlobal['userdata']['est_fee'], 3);
    } else {
      $aGlobal['userdata']['est_block'] = 0;
      $aGlobal['userdata']['est_fee'] = 0;
      $aGlobal['userdata']['est_donation'] = 0;
      $aGlobal['userdata']['est_payout'] = 0;
    }
    break;
  }

  // Site-wide notifications, based on user events
  if ($aGlobal['userdata']['balance']['confirmed'] >= $config['ap_threshold']['max'])
    $_SESSION['POPUP'][] = array('CONTENT' => 'You have exceeded your accounts balance. Please transfer some ' . $config['currency'] . "!", 'TYPE' => 'errormsg');
  if ($user->getUserFailed($_SESSION['USERDATA']['id']) > 0)
    $_SESSION['POPUP'][] = array('CONTENT' => 'You have ' . $user->getUserFailed($_SESSION['USERDATA']['id']) . ' failed login attempts! <a href="?page=account&action=reset_failed">Reset Counter</a>', 'TYPE' => 'errormsg');
}

if ($setting->getValue('maintenance'))
  $_SESSION['POPUP'][] = array('CONTENT' => 'This pool is currently in maintenance mode.', 'TYPE' => 'warning');

// Make it available in Smarty
$smarty->assign('PATH', 'site_assets/' . THEME);
$smarty->assign('GLOBAL', $aGlobal);
?>
