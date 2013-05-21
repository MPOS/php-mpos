<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Fetch data from litecoind
if ($bitcoin->can_connect() === true){
  if (!$dDifficulty = $memcache->get('dDifficulty')) {
    $dDifficulty = $bitcoin->query('getdifficulty');
    $memcache->set('dDifficulty', $dDifficulty);
  }
  if (!$iBlock = $memcache->get('iBlock')) {
    $iBlock = $bitcoin->query('getblockcount');
    $memcache->set('iBlock', $iBlock);
  }
} else {
  $iDifficulty = 1;
  $iBlock = 0;
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to pushpool service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
}

$aHourlyHashRates = $statistics->getHourlyHashrateByAccount($_SESSION['USERDATA']['id']);

// Propagate content our template
$smarty->assign("YOURHASHRATES", $aHourlyHashRates);
$smarty->assign("DIFFICULTY", $dDifficulty);

if ($_SESSION['AUTHENTICATED']) {
  $smarty->assign("CONTENT", "default.tpl");
}
?>
