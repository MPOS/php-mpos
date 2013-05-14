<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Globally available variables
$debug->append('Global smarty variables', 3);

if (!$aRoundShares = $memcache->get('aRoundShares')) {
  $debug->append('Fetching aRoundShares from database');
  $aRoundShares = $share->getRoundShares();
  $memcache->set('aRoundShares', $aRoundShares, 60);
}

if (!$iCurrentActiveWorkers = $memcache->get('iCurrentActiveWorkers')) {
  $debug->append('Fetching iCurrentActiveWorkers from database');
  $iCurrentActiveWorkers = $worker->getCountAllActiveWorkers();
  $memcache->set('iCurrentActiveWorkers', $iCurrentActiveWorkers, 60);
}

if (!$iCurrentPoolHashrate = $memcache->get('iCurrentPoolHashrate')) {
  $debug->append('Fetching iCurrentPoolHashrate from database');
  $iCurrentPoolHashrate =  $statistics->getCurrentHashrate();
  $memcache->set('iCurrentPoolHashrate', $iCurrentPoolHashrate, 60);
}

$aGlobal = array(
  'userdata' => $_SESSION['USERDATA']['id'] ? $user->getUserData($_SESSION['USERDATA']['id']) : array(),
  'slogan' => $settings->getValue('slogan'),
  'websitename' => $settings->getValue('websitename'),
  'ltc_usd' => $settings->getValue('btcesell'),
  'hashrate' => $iCurrentPoolHashrate,
  'sharerate' => $statistics->getCurrentShareRate(),
  'workers' => $iCurrentActiveWorkers,
  'roundshares' => $aRoundShares,
  'statstime' => $settings->getValue('statstime'),
  'motd' => $settings->getValue('motd'),
  'confirmations' => $config['confirmations'],
  'reward' => $config['reward']
);

// Append additional user information not from user table
$aGlobal['userdata']['balance'] = $transaction->getBalance($_SESSION['USERDATA']['id']);

// Make it available in Smarty
$smarty->assign('PATH', 'site_assets/' . THEME);
$smarty->assign('GLOBAL', $aGlobal);
?>
