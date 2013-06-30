<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

if ($bitcoin->can_connect() === true){
  $dDifficulty = $bitcoin->query('getdifficulty');
  if (strtolower($config['currency']) == 'pos')
    $dDifficulty = $dDifficulty['proof-of-work'];
  $iBlock = $bitcoin->query('getblockcount');
} else {
  $dDifficulty = 1;
  $iBlock = 0;
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to litecoind RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
}

$smarty->assign("CURRENTBLOCK", $iBlock);
$smarty->assign("DIFFICULTY", $dDifficulty);
$smarty->assign("CONTENT", "default.tpl");
