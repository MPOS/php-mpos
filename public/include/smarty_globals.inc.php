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

$aGlobal = array(
  'slogan' => $config['website']['slogan'],
  'websitename' => $config['website']['name'],
  'hashrate' => $iCurrentPoolHashrate,
  'sharerate' => $iCurrentPoolShareRate,
  'workers' => $iCurrentActiveWorkers,
  'roundshares' => $aRoundShares,
  'confirmations' => $config['confirmations'],
  'reward' => $config['reward']
);

// We don't want these session infos cached
$aGlobal['userdata'] = $_SESSION['USERDATA']['id'] ? $user->getUserData($_SESSION['USERDATA']['id']) : array();
$aGlobal['userdata']['balance'] = $transaction->getBalance($_SESSION['USERDATA']['id']);

// Other userdata that we can cache savely
$aGlobal['userdata']['shares'] = $statistics->getUserShares($_SESSION['USERDATA']['id']);
$aGlobal['userdata']['hashrate'] = $statistics->getUserHashrate($_SESSION['USERDATA']['id']);

// Make it available in Smarty
$smarty->assign('PATH', 'site_assets/' . THEME);
$smarty->assign('GLOBAL', $aGlobal);
?>
