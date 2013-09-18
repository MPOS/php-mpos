<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  if ($bitcoin->can_connect() === true){
    $dBalance = $bitcoin->query('getbalance');
    $dGetInfo = $bitcoin->query('getinfo');
    if (is_array($dGetInfo) && array_key_exists('newmint', $dGetInfo)) {
      $dNewmint = $dGetInfo['newmint'];
    } else {
      $dNewmint = -1;
    }
  } else {
    $dBalance = 0;
    $dNewmint = -1;
    $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to wallet RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
  }
  // Fetch unconfirmed amount from blocks table
  empty($config['network_confirmations']) ? $confirmations = 120 : $confirmations = $config['network_confirmations'];
  $aBlocksUnconfirmed = $block->getAllUnconfirmed($confirmations);
  $dBlocksUnconfirmedBalance = 0;
  if (!empty($aBlocksUnconfirmed))
    foreach ($aBlocksUnconfirmed as $aData) $dBlocksUnconfirmedBalance += $aData['amount'];

  // Fetch locked balance from transactions
  $dLockedBalance = $transaction->getLockedBalance();

  // Cold wallet balance
  if (! $dColdCoins = $setting->getValue('wallet_cold_coins')) $dColdCoins = 0;
} else {
  $debug->append('Using cached page', 3);
}

$smarty->assign("UNCONFIRMED", $dBlocksUnconfirmedBalance);
$smarty->assign("BALANCE", $dBalance);
$smarty->assign("COLDCOINS", $dColdCoins);
$smarty->assign("LOCKED", $dLockedBalance);
$smarty->assign("NEWMINT", $dNewmint);

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");

?>
