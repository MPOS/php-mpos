<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Globally available variables
$debug->append('Global smarty variables', 3);

// Store some stuff in memcache prior to assigning it to Smarty
if (!$aRoundShares = $memcache->get('aRoundShares')) {
  $debug->append('STA Fetching aRoundShares from database');
  $aRoundShares = $statistics->getRoundShares();
  $debug->append('END Fetching aRoundShares from database');
  $memcache->set('aRoundShares', $aRoundShares, 90);
}

if (!$iCurrentActiveWorkers = $memcache->get('iCurrentActiveWorkers')) {
  $debug->append('STA Fetching iCurrentActiveWorkers from database');
  $iCurrentActiveWorkers = $worker->getCountAllActiveWorkers();
  $debug->append('END Fetching iCurrentActiveWorkers from database');
  $memcache->set('iCurrentActiveWorkers', $iCurrentActiveWorkers, 80);
}

if (!$iCurrentPoolHashrate = $memcache->get('iCurrentPoolHashrate')) {
  $debug->append('STA Fetching iCurrentPoolHashrate from database');
  $iCurrentPoolHashrate =  $statistics->getCurrentHashrate();
  $debug->append('END Fetching iCurrentPoolHashrate from database');
  $memcache->set('iCurrentPoolHashrate', $iCurrentPoolHashrate, 90);
}

if (!$iCurrentPoolShareRate = $memcache->get('iCurrentPoolShareRate')) {
  $debug->append('STA Fetching iCurrentPoolShareRate from database');
  $iCurrentPoolShareRate = $statistics->getCurrentShareRate();
  $debug->append('END Fetching iCurrentPoolShareRate from database');
  $memcache->set('iCurrentPoolShareRate', $iCurrentPoolShareRate, 90);
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
if (!$aGlobal['userdata']['shares'] = $memcache->get('global_' . $_SESSION['USERDATA']['id'] . '_shares')) {
  $debug->append('STA Loading user shares from database');
  $aGlobal['userdata']['shares'] = $statistics->getUserShares($_SESSION['USERDATA']['id']);
  $debug->append('END Loading user shares from database');
  $memcache->set('global_' . $_SESSION['USERDATA']['id'] . '_shares', $aGlobal['userdata']['shares'], 80);
}

if (!$aGlobal['userdata']['hashrate'] = $memcache->get('global_' . $_SESSION['USERDATA']['id'] . '_hashrate') ) {
  $debug->append('STA Loading user hashrate from database');
  $aGlobal['userdata']['hashrate'] = $statistics->getUserHashrate($_SESSION['USERDATA']['id']);
  $debug->append('END Loading user hashrate from database');
  $memcache->set('global_' . $_SESSION['USERDATA']['id'] . '_hashrate', $aGlobal['userdata']['hashrate'], 70);
}

// Make it available in Smarty
$smarty->assign('PATH', 'site_assets/' . THEME);
$smarty->assign('GLOBAL', $aGlobal);
?>
