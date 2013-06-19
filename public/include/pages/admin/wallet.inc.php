<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

if ($bitcoin->can_connect() === true){
  $dBalance = $bitcoin->query('getbalance');
} else {
  $dBalance = 0;
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to litecoind RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
}

$smarty->assign("BALANCE", $dBalance);
$smarty->assign("LOCKED", $transaction->getLockedBalance());

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
