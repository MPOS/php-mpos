<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Globally available variables
$debug->append('Global smarty variables', 3);

// Store some stuff in memcache prior to assigning it to Smarty
if (!$aRoundShares = $memcache->get('aRoundShares')) {
  $debug->append('Fetching aRoundShares from database');
  $aRoundShares = $statistics->getRoundShares();
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

if (!$iCurrentPoolShareRate = $memcache->get('iCurrentPoolShareRate')) {
  $debug->append('Fetching iCurrentPoolShareRate from database');
  $iCurrentPoolShareRate = $statistics->getCurrentShareRate();
  $memcache->set('iCurrentPoolShareRate', $iCurrentPoolShareRate, 60);
}

$aGlobal = array(
  'slogan' => $settings->getValue('slogan'),
  'websitename' => $settings->getValue('websitename'),
  'ltc_usd' => $settings->getValue('btcesell'),
  'hashrate' => $iCurrentPoolHashrate,
  'sharerate' => $iCurrentPoolShareRate,
  'workers' => $iCurrentActiveWorkers,
  'roundshares' => $aRoundShares,
  'statstime' => $settings->getValue('statstime'),
  'motd' => $settings->getValue('motd'),
  'confirmations' => $config['confirmations'],
  'reward' => $config['reward']
);

// We don't want the session infos cached
$aGlobal['userdata'] = $_SESSION['USERDATA']['id'] ? $user->getUserData($_SESSION['USERDATA']['id']) : array();

// Balance should also not be cached
$aGlobal['userdata']['balance'] = $transaction->getBalance($_SESSION['USERDATA']['id']);

// Other userdata that we can cache savely
if (!$aGlobal['userdata']['hashrate'] = $memcache->get('global_' . $_SESSION['USERDATA']['id'] . '_hashrate') ) {
  $aGlobal['userdata']['hashrate'] = $statistics->getUserHashrate($_SESSION['USERDATA']['id']);
  $memcache->set('global_' . $_SESSION['USERDATA']['id'] . '_hashrate', $aGlobal['userdata']['hashrate'], 60);
}

// Make it available in Smarty
$smarty->assign('PATH', 'site_assets/' . THEME);
$smarty->assign('GLOBAL', $aGlobal);
?>
