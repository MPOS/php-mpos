<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  if ($bitcoin->can_connect() === true){
    $dDifficulty = $bitcoin->getdifficulty();
    $iBlock = $bitcoin->getblockcount();
  } else {
    $dDifficulty = 1;
    $iBlock = 0;
    $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to litecoind RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
  }
  $smarty->assign("CURRENTBLOCK", $iBlock);
  $smarty->assign("DIFFICULTY", $dDifficulty);
} else {
  $debug->append('Using cached page', 3);
}

$smarty->assign("CONTENT", "default.tpl");
?>
