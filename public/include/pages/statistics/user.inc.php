<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Fetch data from litecoind
if ($bitcoin->can_connect() === true){
  if (!$dDifficulty = $memcache->get('dDifficulty')) {
    $dDifficulty = $bitcoin->query('getdifficulty');
    $memcache->set('dDifficulty', $dDifficulty, 60);
  }
  if (!$iBlock = $memcache->get('iBlock')) {
    $iBlock = $bitcoin->query('getblockcount');
    $memcache->set('iBlock', $iBlock, 60);
  }
} else {
  $iDifficulty = 1;
  $iBlock = 0;
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to pushpool service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
}

if (!$aHourlyHashRates = $memcache->get('mmcfe_' . $_SESSION['USERDATA']['id'] . '_hourlyhashrate')) {
  $debug->append('STA Fetching hourly hashrates from database');
  $aHourlyHashRates = $statistics->getHourlyHashrateByAccount($_SESSION['USERDATA']['id']);
  $memcache->set('mmcfe_' . $_SESSION['USERDATA']['id'] . '_hourlyhashrate', $aHourlyHashRates, 600);
  $debug->append('END Fetching hourly hashrates from database');
}

// Propagate content our template
$smarty->assign("YOURHASHRATES", $aHourlyHashRates);
$smarty->assign("DIFFICULTY", $dDifficulty);

if ($_SESSION['AUTHENTICATED']) {
  $smarty->assign("CONTENT", "default.tpl");
}
?>
