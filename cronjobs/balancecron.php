#!/usr/bin/php
<?php
// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');

// Check For RPC Connection
if ($bitcoin->can_connect() === true){
$dBalance = $bitcoin->query('getbalance');
// Check Wallet Balance
echo("The Wallet Balance is " .$dBalance. "\n");
$dGetInfo = $bitcoin->query('getinfo');

// Check for POS Mint
if (is_array($dGetInfo) && array_key_exists('newmint', $dGetInfo)) {
$dNewmint = $dGetInfo['newmint'];

echo("Current Mint is: " .$dNewmint);
} else {
  $dNewmint = -1;
}
} else {
  $dBalance = 0;
  $dNewmint = -1;
  echo('Unable to connect to wallet RPC service');
}

// Fetch unconfirmed amount from blocks table
empty($config['network_confirmations']) ? $confirmations = 120 : $confirmations = $config['network_confirmations'];
$aBlocksUnconfirmed = $block->getAllUnconfirmed($confirmations);
$dBlocksUnconfirmedBalance = 0;
if (!empty($aBlocksUnconfirmed))
foreach ($aBlocksUnconfirmed as $aData) $dBlocksUnconfirmedBalance += $aData['amount'];

// Fetch locked balance from transactions
$dLockedBalance = $transaction->getLockedBalance();
echo("The Locked Wallet Balance for Users is: " .$dLockedBalance. "\n");
// Fetch Final Wallet Balance after Transfer
$aFloat = $dLockedBalance + $config['coldwallet']['float'];
echo("The Locked Wallet Balance & Float amounts to: " .$aFloat. "\n");

// Send Liquid Balance
$send = $dBalance - $aFloat ;
echo("Final Sending Amount is : " .$send. "\n");

$bitcoin->query('sendtoaddress',$config['coldwallet']['address'], $send)
?>
