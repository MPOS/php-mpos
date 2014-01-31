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

// Fetch our manual payouts, process them
if ($setting->getValue('disable_manual_payouts') != 1 && $aManualPayouts = $transaction->getMPQueue()) {
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
      if (!$transaction_id = $transaction->createDebitAPRecord($aUserData['id'], $aUserData['coin_address'], $aUserData['confirmed'] - $config['txfee_manual'])) {
        $log->logFatal('    failed to fullt debit user ' . $aUserData['username'] . ': ' . $transaction->getCronError());
        $monitoring->endCronjob($cron_name, 'E0064', 1, true);
      } else {
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
      }
    } else {
      $log->logInfo('    failed to validate address for user: ' . $aUserData['username']);
      continue;
    }
  }
}

// Fetch our auto payouts, process them
if ($setting->getValue('disable_auto_payouts') != 1 && $aAutoPayouts = $transaction->getAPQueue()) {
  $log->logInfo('  found ' . count($aAutoPayouts) . ' queued auto payouts');
  $mask = '    | %-10.10s | %-25.25s | %-20.20s | %-40.40s | %-20.20s |';
  $log->logInfo(sprintf($mask, 'UserID', 'Username', 'Balance', 'Address', 'Threshold'));
  foreach ($aAutoPayouts as $aUserData) {
    $transaction_id = NULL;
    $rpc_txid = NULL;
    $log->logInfo(sprintf($mask, $aUserData['id'], $aUserData['username'], $aUserData['confirmed'], $aUserData['coin_address'], $aUserData['ap_threshold']));
    if ($bitcoin->validateaddress($aUserData['coin_address'])) {
      if (!$transaction_id = $transaction->createDebitAPRecord($aUserData['id'], $aUserData['coin_address'], $aUserData['confirmed'] - $config['txfee_manual'])) {
        $log->logFatal('    failed to fully debit user ' . $aUserData['username'] . ': ' . $transaction->getCronError());
        $monitoring->endCronjob($cron_name, 'E0064', 1, true);
      } else {
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
      }
    } else {
      $log->logInfo('    failed to validate address for user: ' . $aUserData['username']);
      continue;
    }
  }
}

require_once('cron_end.inc.php');
