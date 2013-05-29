<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Fetch data from litecoind
if ($bitcoin->can_connect() === true){
  $dDifficulty = $bitcoin->getdifficulty();
  $iBlock = $bitcoin->getblockcount();
} else {
  $iDifficulty = 1;
  $iBlock = 0;
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to litecoind RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
}

$aHourlyHashRates = $statistics->getHourlyHashrateByAccount($_SESSION['USERDATA']['id']);

// Propagate content our template
$smarty->assign("YOURHASHRATES", $aHourlyHashRates);
$smarty->assign("DIFFICULTY", $dDifficulty);

if ($_SESSION['AUTHENTICATED']) {
  $smarty->assign("CONTENT", "default.tpl");
}
?>
