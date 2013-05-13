<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Globally available variables
$debug->append('Global smarty variables', 3);

if (!$iRoundShares = $memcache->get('iRoundShares')) {
  $debug->append('Fetching iRoundShares from database');
  $iRoundShares = $share->getRoundShares();
  $memcache->set('iRoundShares', $iRoundShares, 60);
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
  'workers' => $iCurrentActiveWorkers,
  'currentroundshares' => $iRoundShares,
  'statstime' => $settings->getValue('statstime'),
  'motd' => $settings->getValue('motd'),
  'confirmations' => $config['confirmations']
);
// Append additional user information not from user table
$aGlobal['userdata']['balance'] = $transaction->getBalance($_SESSION['USERDATA']['id']);

// Make it available in Smarty
$smarty->assign('PATH', 'site_assets/' . THEME);
$smarty->assign('GLOBAL', $aGlobal);
?>
