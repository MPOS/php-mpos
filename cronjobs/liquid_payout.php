#!/usr/bin/php
<?php
// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');

// Simple configuration check
if (!is_array($config['coldwallet'])) {
  $log->logFatal('Missing config option: coldwallet');
  $monitoring->endCronjob($cron_name, 'E0075', 1, true);
}

// Check RPC connection
if ($bitcoin->can_connect() !== true) {
  $log->logFatal('Unable to connect to RPC server, exiting');
  $monitoring->endCronjob($cron_name, 'E0006', 1, true);
} else {
  // Check Wallet Balance
  $dBalance = $bitcoin->getbalance();
  $log->logDebug('The wallet balance is: ' .$dBalance);

  // Do we have anything available at all?
  if (! ($dBalance > 0)) {
    $log->logInfo('No coins available in wallet');
    $monitoring->endCronjob($cron_name, 'E0076', 0, true, false);
  }

  // Check for POS Mint
  $dGetInfo = $bitcoin->getinfo();
  if (is_array($dGetInfo) && array_key_exists('newmint', $dGetInfo)) {
    $dNewmint = $dGetInfo['newmint'];
    $log->logDebug('Current Mint is: ' . $dNewmint);
  } else {
    $dNewmint = -1;
  }
}

// Fetch locked balance from transactions
$dLockedBalance = $transaction->getLockedBalance();
$log->logDebug('The locked wallet balance for users is: ' . $dLockedBalance);

// Fetch Final Wallet Balance after Transfer
$dFloat = $dLockedBalance + $config['coldwallet']['reserve'];
$dThreshold = $config['coldwallet']['threshold'];
$log->logDebug('The locked wallet balance + reserves amounts to: ' . $dFloat);

// Send Liquid Balance
$sendAddress = $config['coldwallet']['address'];
$send = $dBalance - $dFloat ;
$log->logDebug('Final Sending Amount is : ' . $send);

if($send > $dThreshold) {
  if (!empty($sendAddress)) {
    try {
      $bitcoin->sendtoaddress($sendAddress, $send);
    } catch (Exception $e) {
      $log->logError('Failed to send coins to address, skipping liquid assets payout');
    }
    $log->logInfo('Sent out ' . $send . ' liquid assets');
  } else {
    $log->logDebug('No wallet address set');
  }
} else {
  $log->logDebug('Final sending amount not exceeding threshold: ' . $send);
}

// Cron cleanup and monitoring
require_once('cron_end.inc.php');
?>
