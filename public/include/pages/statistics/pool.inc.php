<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

if ($bitcoin->can_connect() === true){
  $iDifficulty = $bitcoin->query('getdifficulty');
  $iBlock = $bitcoin->query('getblockcount');
} else {
  $iDifficulty = 1;
  $iBlock = 0;
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to pushpool service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
}

$smarty->assign("CURRENTBLOCK", $iBlock);
$smarty->assign("DIFFICULTY", $iDifficulty);

if ($_SESSION['AUTHENTICATED']) {
  $smarty->assign("CONTENT", "authenticated.tpl");
} else {
  $smarty->assign("CONTENT", "default.tpl");
}
?>
