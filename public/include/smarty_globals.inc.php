<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Globally available variables
$debug->append('Global smarty variables', 3);

// Fetch some data
$aRoundShares = $statistics->getRoundShares();
$iCurrentActiveWorkers = $worker->getCountAllActiveWorkers();
$iCurrentPoolHashrate =  $statistics->getCurrentHashrate();
$iCurrentPoolShareRate = $statistics->getCurrentShareRate();
if ($bitcoin->can_connect() === true){
    $dDifficulty = $bitcoin->query('getdifficulty');
} else {
    $dDifficulty = 1;
}

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

  // Some estimations
  $aGlobal['userdata']['est_block'] = round(( (int)$aGlobal['userdata']['shares']['valid'] / (int)$aRoundShares['valid'] ) * (int)$config['reward'], 3);
  $aGlobal['userdata']['est_fee'] = round(($config['fees'] / 100) * $aGlobal['userdata']['est_block'], 3);
  $aGlobal['userdata']['est_donation'] = round((( $aGlobal['userdata']['donate_percent'] / 100) * ($aGlobal['userdata']['est_block'] - $aGlobal['userdata']['est_fee'])), 3);
  $aGlobal['userdata']['est_payout'] = round($aGlobal['userdata']['est_block'] - $aGlobal['userdata']['est_donation'] - $aGlobal['userdata']['est_fee'], 3);
}

// Make it available in Smarty
$smarty->assign('PATH', 'site_assets/' . THEME);
$smarty->assign('GLOBAL', $aGlobal);
?>
