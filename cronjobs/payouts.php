#!/usr/bin/php
<?php

/*

Copyright:: 2013, Sebastian Grewe

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

 */

// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');

if ($setting->getValue('disable_payouts') == 1) {
  $log->logInfo(" payouts disabled via admin panel");
  $monitoring->endCronjob($cron_name, 'E0009', 0, true, false);
}
$log->logInfo("Starting Payout...");
if ($bitcoin->can_connect() !== true) {
  $log->logFatal(" unable to connect to RPC server, exiting");
  $monitoring->endCronjob($cron_name, 'E0006', 1, true);
}

// Check and see if the sendmany RPC method is available
// This does not test if it actually works too!
$sendmanyAvailable = ((strpos($bitcoin->help('sendmany'), 'unknown') === FALSE) ? true : false);
if ($sendmanyAvailable)
  $log->logDebug('  sendmany available in coind help command');

if (!$dWalletBalance = $bitcoin->getrealbalance())
  $dWalletBalance = 0;

// Fetch unconfirmed amount from blocks table
empty($config['network_confirmations']) ? $confirmations = 120 : $confirmations = $config['network_confirmations'];
$aBlocksUnconfirmed = $block->getAllUnconfirmed($confirmations);
$dBlocksUnconfirmedBalance = 0;
if (!empty($aBlocksUnconfirmed))foreach ($aBlocksUnconfirmed as $aData) $dBlocksUnconfirmedBalance += $aData['amount'];
if ($config['getbalancewithunconfirmed']) {
  $dWalletBalance -= $dBlocksUnconfirmedBalance;
}
// Fetch Newmint
$aGetInfo = $bitcoin->getinfo();
if (is_array($aGetInfo) && array_key_exists('newmint', $aGetInfo)) {
  $dWalletBalance += $aGetInfo['newmint'];
}

// Fetch outstanding manual-payouts
$aManualPayouts = $transaction->getMPQueue($config['payout']['txlimit_manual']);

// Fetch our manual payouts, process them
if ($setting->getValue('disable_manual_payouts') != 1 && $aManualPayouts) {
  // Calculate our sum first
  $dMPTotalAmount = 0;
  $aSendMany = NULL;
  foreach ($aManualPayouts as $aUserData) $dMPTotalAmount += $aUserData['confirmed'];
  if ($dMPTotalAmount > $dWalletBalance) {
    $log->logError(" Wallet does not cover MP payouts - Payout: " . $dMPTotalAmount . " - Balance: " . $dWalletBalance);
    $monitoring->endCronjob($cron_name, 'E0079', 1, true);
  }

  $log->logInfo("Manual Payout Sum: " . $dMPTotalAmount . " | Liquid Assets: " . $dWalletBalance . " | Wallet Balance: " . ($dWalletBalance + $dBlocksUnconfirmedBalance) . " | Unconfirmed: " . $dBlocksUnconfirmedBalance);
  $log->logInfo('  found ' . count($aManualPayouts) . ' queued manual payouts');
  $mask = '    | %-10.10s | %-25.25s | %-20.20s | %-40.40s | %-20.20s |';
  $log->logInfo(sprintf($mask, 'UserID', 'Username', 'Balance', 'Address', 'Payout ID'));
  foreach ($aManualPayouts as $aUserData) {
    $transaction_id = NULL;
    $rpc_txid = NULL;
    $log->logInfo(sprintf($mask, $aUserData['id'], $aUserData['username'], $aUserData['confirmed'], $aUserData['coin_address'], $aUserData['payout_id']));
    if (!$oPayout->setProcessed($aUserData['payout_id'])) {
      $log->logFatal('    unable to mark transactions ' . $aData['id'] . ' as processed. ERROR: ' . $oPayout->getCronError());
      $monitoring->endCronjob($cron_name, 'E0010', 1, true);
    }
    if ($bitcoin->validateaddress($aUserData['coin_address'])) {
      if (!$transaction_id = $transaction->createDebitMPRecord($aUserData['id'], $aUserData['coin_address'], $aUserData['confirmed'])) {
        $log->logFatal('    failed to fullt debit user ' . $aUserData['username'] . ': ' . $transaction->getCronError());
        $monitoring->endCronjob($cron_name, 'E0064', 1, true);
      } else if (!$config['sendmany']['enabled'] || !$sendmanyAvailable) {
        // Run the payouts from RPC now that the user is fully debited
        try {
          $rpc_txid = $bitcoin->sendtoaddress($aUserData['coin_address'], $aUserData['confirmed'] - $config['txfee_manual']);
        } catch (Exception $e) {
          $log->logError('E0078: RPC method did not return 200 OK: Address: ' . $aUserData['coin_address'] . ' ERROR: ' . $e->getMessage());
          // Remove this line below if RPC calls are failing but transactions are still added to it
          // Don't blame MPOS if you run into issues after commenting this out!
          $monitoring->endCronjob($cron_name, 'E0078', 1, true);
        }
        // Update our transaction and add the RPC Transaction ID
        if (empty($rpc_txid) || !$transaction->setRPCTxId($transaction_id, $rpc_txid))
          $log->logError('Unable to add RPC transaction ID ' . $rpc_txid . ' to transaction record ' . $transaction_id . ': ' . $transaction->getCronError());
      } else {
        // We don't run sendtoaddress but run sendmany later
        $aSendMany[$aUserData['coin_address']] = $aUserData['confirmed'] - $config['txfee_manual'];
        $aTransactions[] = $transaction_id;
      }
    } else {
      $log->logInfo('    failed to validate address for user: ' . $aUserData['username']);
      continue;
    }
  }
  if ($config['sendmany']['enabled'] && $sendmanyAvailable && is_array($aSendMany)) {
    try {
      $rpc_txid = $bitcoin->sendmany('', $aSendMany);
    } catch (Exception $e) {
      $log->logError('E0078: RPC method sendmany did not return 200 OK: Address: ' . $aUserData['coin_address'] . ' ERROR: ' . $e->getMessage());
      // Remove this line below if RPC calls are failing but transactions are still added to it
      // Don't blame MPOS if you run into issues after commenting this out!
      $monitoring->endCronjob($cron_name, 'E0078', 1, true);
    }
    $log->logInfo('  payout succeeded with RPC TXID: ' . $rpc_txid);
    foreach ($aTransactions as $iTransactionID) {
      if (empty($rpc_txid) || !$transaction->setRPCTxId($iTransactionID, $rpc_txid))
        $log->logError('Unable to add RPC transaction ID ' . $rpc_txid . ' to transaction record ' . $iTransactionID . ': ' . $transaction->getCronError());
    }
  }
}

if (!$dWalletBalance = $bitcoin->getrealbalance())
  $dWalletBalance = 0;

// Fetch unconfirmed amount from blocks table
empty($config['network_confirmations']) ? $confirmations = 120 : $confirmations = $config['network_confirmations'];
$aBlocksUnconfirmed = $block->getAllUnconfirmed($confirmations);
$dBlocksUnconfirmedBalance = 0;
if (!empty($aBlocksUnconfirmed))foreach ($aBlocksUnconfirmed as $aData) $dBlocksUnconfirmedBalance += $aData['amount'];
if ($config['getbalancewithunconfirmed']) {
  $dWalletBalance -= $dBlocksUnconfirmedBalance;
}
// Fetch Newmint
$aGetInfo = $bitcoin->getinfo();
if (is_array($aGetInfo) && array_key_exists('newmint', $aGetInfo)) {
  $dWalletBalance += $aGetInfo['newmint'];
}

// Fetch outstanding auto-payouts
$aAutoPayouts = $transaction->getAPQueue($config['payout']['txlimit_auto']);

// Fetch our auto payouts, process them
if ($setting->getValue('disable_auto_payouts') != 1 && $aAutoPayouts) {
  $aSendMany = NULL;
  // Calculate our sum first
  $dAPTotalAmount = 0;
  foreach ($aAutoPayouts as $aUserData) $dAPTotalAmount += $aUserData['confirmed'];
  if ($dAPTotalAmount > $dWalletBalance) {
    $log->logError(" Wallet does not cover AP payouts - Payout: " . $dAPTotalAmount . " - Balance: " . $dWalletBalance);
    $monitoring->endCronjob($cron_name, 'E0079', 1, true);
  }

  $log->logInfo("Auto Payout Sum: " . $dAPTotalAmount . " | Liquid Assets: " . $dWalletBalance . " | Wallet Balance: " . ($dWalletBalance + $dBlocksUnconfirmedBalance) . " | Unconfirmed: " . $dBlocksUnconfirmedBalance);
  $log->logInfo('  found ' . count($aAutoPayouts) . ' queued auto payouts');
  $mask = '    | %-10.10s | %-25.25s | %-20.20s | %-40.40s | %-20.20s |';
  $log->logInfo(sprintf($mask, 'UserID', 'Username', 'Balance', 'Address', 'Threshold'));
  foreach ($aAutoPayouts as $aUserData) {
    $transaction_id = NULL;
    $rpc_txid = NULL;
    $log->logInfo(sprintf($mask, $aUserData['id'], $aUserData['username'], $aUserData['confirmed'], $aUserData['coin_address'], $aUserData['ap_threshold']));
    if ($bitcoin->validateaddress($aUserData['coin_address'])) {
      if (!$transaction_id = $transaction->createDebitAPRecord($aUserData['id'], $aUserData['coin_address'], $aUserData['confirmed'])) {
        $log->logFatal('    failed to fully debit user ' . $aUserData['username'] . ': ' . $transaction->getCronError());
        $monitoring->endCronjob($cron_name, 'E0064', 1, true);
      } else if (!$config['sendmany']['enabled'] || !$sendmanyAvailable) {
        // Run the payouts from RPC now that the user is fully debited
        try {
          $rpc_txid = $bitcoin->sendtoaddress($aUserData['coin_address'], $aUserData['confirmed'] - $config['txfee_auto']);
        } catch (Exception $e) {
          $log->logError('E0078: RPC method did not return 200 OK: Address: ' . $aUserData['coin_address'] . ' ERROR: ' . $e->getMessage());
          // Remove this line below if RPC calls are failing but transactions are still added to it
          // Don't blame MPOS if you run into issues after commenting this out!
          $monitoring->endCronjob($cron_name, 'E0078', 1, true);
        }
        // Update our transaction and add the RPC Transaction ID
        if (empty($rpc_txid) || !$transaction->setRPCTxId($transaction_id, $rpc_txid))
          $log->logError('Unable to add RPC transaction ID ' . $rpc_txid . ' to transaction record ' . $transaction_id . ': ' . $transaction->getCronError());
      } else {
        // We don't run sendtoaddress but run sendmany later
        $aSendMany[$aUserData['coin_address']] = $aUserData['confirmed'] - $config['txfee_auto'];
        $aTransactions[] = $transaction_id;
      }
    } else {
      $log->logInfo('    failed to validate address for user: ' . $aUserData['username']);
      continue;
    }
  }
  if ($config['sendmany']['enabled'] && $sendmanyAvailable && is_array($aSendMany)) {
    try {
      $rpc_txid = $bitcoin->sendmany('', $aSendMany);
    } catch (Exception $e) {
      $log->logError('E0078: RPC method sendmany did not return 200 OK: Address: ' . $aUserData['coin_address'] . ' ERROR: ' . $e->getMessage());
      // Remove this line below if RPC calls are failing but transactions are still added to it
      // Don't blame MPOS if you run into issues after commenting this out!
      $monitoring->endCronjob($cron_name, 'E0078', 1, true);
    }
    $log->logInfo('  payout succeeded with RPC TXID: ' . $rpc_txid);
    foreach ($aTransactions as $iTransactionID) {
      if (empty($rpc_txid) || !$transaction->setRPCTxId($iTransactionID, $rpc_txid))
        $log->logError('Unable to add RPC transaction ID ' . $rpc_txid . ' to transaction record ' . $iTransactionID . ': ' . $transaction->getCronError());
    }
  }
}

require_once('cron_end.inc.php');
