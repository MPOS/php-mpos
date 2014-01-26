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
if ($setting->getValue('disable_manual_payouts') != 1) {
  // Fetch outstanding payout requests
  if ($aPayouts = $oPayout->getUnprocessedPayouts()) {
    if (count($aPayouts) > 0) {
      $log->logInfo("\tStarting Manual Payments...");
      $log->logInfo("\tAccount ID\tUsername\tBalance\t\tCoin Address");
      foreach ($aPayouts as $aData) {
        $aBalance = $transaction->getBalance($aData['account_id']);
        $dBalance = $aBalance['confirmed'];
        $aData['coin_address'] = $user->getCoinAddress($aData['account_id']);
        $aData['username'] = $user->getUserName($aData['account_id']);
        if ($dBalance > $config['txfee_manual']) {
          // To ensure we don't run this transaction again, lets mark it completed
          if (!$oPayout->setProcessed($aData['id'])) {
            $log->logFatal('unable to mark transactions ' . $aData['id'] . ' as processed. ERROR: ' . $oPayout->getCronError());
            $monitoring->endCronjob($cron_name, 'E0010', 1, true);
          }
          $log->logInfo("\t" . $aData['account_id'] . "\t\t" . $aData['username'] . "\t" . $dBalance . "\t\t" . $aData['coin_address']);
          try {
            $txid = $bitcoin->sendtoaddress($aData['coin_address'], $dBalance - $config['txfee_manual']);
          } catch (Exception $e) {
            $log->logError('E0078: RPC method did not return 200 OK: Address: ' . $aData['coin_address'] . ' ERROR: ' . $e->getMessage());
            $monitoring->endCronjob($cron_name, 'E0078', 1, true);
          }
          if ($transaction->addTransaction($aData['account_id'], $dBalance - $config['txfee_manual'], 'Debit_MP', NULL, $aData['coin_address'], $txid) && $transaction->addTransaction($aData['account_id'], $config['txfee_manual'], 'TXFee', NULL, $aData['coin_address'])) {
            // Mark all older transactions as archived
            if (!$transaction->setArchived($aData['account_id'], $transaction->insert_id))
              $log->logError('Failed to mark transactions for #' . $aData['account_id'] . ' prior to #' . $transaction->insert_id . ' as archived. ERROR: ' . $transaction->getCronError());
            // Notify user via  mail
            $aMailData['email'] = $user->getUserEmail($user->getUserName($aData['account_id']));
            $aMailData['subject'] = 'Manual Payout Completed';
            $aMailData['amount'] = $dBalance - $config['txfee_manual'];
            $aMailData['payout_id'] = $aData['id'];
            if (!$notification->sendNotification($aData['account_id'], 'manual_payout', $aMailData))
              $log->logError('Failed to send notification email to users address: ' . $aMailData['email'] . 'ERROR: ' . $notification->getCronError());
            // Recheck the users balance to make sure it is now 0
            if (!$aBalance = $transaction->getBalance($aData['account_id'])) {
              $log->logFatal('Failed to fetch balance for account ' . $aData['account_id'] . '. ERROR: ' . $transaction->getCronError());
              $monitoring->endCronjob($cron_name, 'E0065', 1, true);
            }
            if ($aBalance['confirmed'] > 0) {
              $log->logFatal('User has a remaining balance of ' . $aBalance['confirmed'] . ' after a successful payout!');
              $monitoring->endCronjob($cron_name, 'E0065', 1, true);
            }
          } else {
            $log->logFatal('Failed to add new Debit_MP transaction in database for user ' . $user->getUserName($aData['account_id']) . ' ERROR: ' . $transaction->getCronError());
            $monitoring->endCronjob($cron_name, 'E0064', 1, true);
          }
        }

      }
    }
  } else if (empty($aPayouts)) {
    $log->logInfo("\tStopping Payments. No Payout Requests Found.");
  } else {
    $log->logFatal("\tFailed Processing Manual Payment Queue...Aborting...");
    $monitoring->endCronjob($cron_name, 'E0050', 1, true);
  }
  if (count($aPayouts > 0)) $log->logDebug(" found " . count($aPayouts) . " queued manual payout requests");
} else {
  $log->logDebug("Manual payouts are disabled via admin panel");
}
if ($setting->getValue('disable_auto_payouts') != 1) {
  // Fetch all users balances
  if ($users = $transaction->getAPQueue()) {
    if (!empty($users)) {
      if (count($users) > 0) $log->logDebug(" found " . count($users) . " queued payout(s)");
      // Go through users and run transactions
      $log->logInfo("Starting Payments...");
      $log->logInfo("\tUserID\tUsername\tBalance\tThreshold\tAddress");
      foreach ($users as $aUserData) {
        $dBalance = $aUserData['confirmed'];
        $log->logInfo("\t" . $aUserData['id'] . "\t" . $aUserData['username'] . "\t" . $dBalance . "\t" . $aUserData['ap_threshold'] . "\t\t" . $aUserData['coin_address']);
        // Only run if balance meets threshold and can pay the potential transaction fee
        if ($dBalance > $aUserData['ap_threshold'] && $dBalance > $config['txfee_auto']) {
          // Validate address against RPC
          try {
          $aStatus = $bitcoin->validateaddress($aUserData['coin_address']);
          if (!$aStatus['isvalid']) {
            $log->logError('Failed to verify this users coin address, skipping payout');
            continue;
            }
          } catch (Exception $e) {
            $log->logError('Failed to verify this users coin address, skipping payout');
            continue;
          }
          // Send balance, fees are reduced later by RPC Server
          try {
            $txid = $bitcoin->sendtoaddress($aUserData['coin_address'], $dBalance - $config['txfee_auto']);
          } catch (Exception $e) {
            $log->logError('E0078: RPC method did not return 200 OK: Address: ' . $aUserData['coin_address'] . ' ERROR: ' . $e->getMessage());
            $monitoring->endCronjob($cron_name, 'E0078', 1, true);
          }
          // Create transaction record
          if ($transaction->addTransaction($aUserData['id'], $dBalance - $config['txfee_auto'], 'Debit_AP', NULL, $aUserData['coin_address'], $txid) && $transaction->addTransaction($aUserData['id'], $config['txfee_auto'], 'TXFee', NULL, $aUserData['coin_address'])) {
            // Mark all older transactions as archived
            if (!$transaction->setArchived($aUserData['id'], $transaction->insert_id))
              $log->logError('Failed to mark transactions for user #' . $aUserData['id'] . ' prior to #' . $transaction->insert_id . ' as archived. ERROR: ' . $transaction->getCronError());
            // Notify user via  mail
            $aMailData['email'] = $user->getUserEmail($user->getUserName($aUserData['id']));
            $aMailData['subject'] = 'Auto Payout Completed';
            $aMailData['amount'] = $dBalance - $config['txfee_auto'];
            if (!$notification->sendNotification($aUserData['id'], 'auto_payout', $aMailData))
              $log->logError('Failed to send notification email to users address: ' . $aMailData['email'] . ' ERROR: ' . $notification->getCronError());
            // Recheck the users balance to make sure it is now 0
            $aBalance = $transaction->getBalance($aUserData['id']);
            if ($aBalance['confirmed'] > 0) {
              $log->logFatal('User has a remaining balance of ' . $aBalance['confirmed'] . ' after a successful payout!');
              $monitoring->endCronjob($cron_name, 'E0065', 1, true);
            }
          } else {
            $log->logFatal('Failed to add new Debit_AP transaction in database for user ' . $user->getUserName($aUserData['id']) . ' ERROR: ' . $transaction->getCronError());
            $monitoring->endCronjob($cron_name, 'E0064', 1, true);
          }
        }
      }
    }
  } else if(empty($users)) {
    $log->logInfo("\tSkipping payments. No Auto Payments Eligible.");
    $log->logDebug("Users have not configured their AP > 0");
  } else{
    $log->logFatal("\tFailed Processing Auto Payment Payment Queue. ERROR: " . $transaction->getCronError());
    $monitoring->endCronjob($cron_name, 'E0050', 1, true);
  }
} else {
  $log->logDebug("Auto payouts disabled via admin panel");
}

$log->logInfo("Completed Payouts");
// Cron cleanup and monitoring
require_once('cron_end.inc.php');
?>
