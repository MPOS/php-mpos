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

if ($setting->getValue('disable_ap') == 1) {
  $log->logInfo(" auto payout disabled via admin panel");
  $monitoring->setStatus($cron_name . "_active", "yesno", 0);
  $monitoring->setStatus($cron_name . "_message", "message", "Auto-Payout disabled");
  $monitoring->setStatus($cron_name . "_status", "okerror", 1);
  $monitoring->setStatus($cron_name . "_endtime", "date", time());
  exit(0);
}

if ($bitcoin->can_connect() !== true) {
  $log->logFatal(" unable to connect to RPC server, exiting");
  $monitoring->setStatus($cron_name . "_active", "yesno", 0);
  $monitoring->setStatus($cron_name . "_message", "message", "Unable to connect to RPC server");
  $monitoring->setStatus($cron_name . "_status", "okerror", 1);
  exit(1);
}

// Fetch all users with setup AP
$users = $user->getAllAutoPayout();

// Quick summary
if (count($users) > 0) $log->logInfo(" found " . count($users) . " queued payout(s)");

// Go through users and run transactions
if (! empty($users)) {
  $log->logInfo("\tUserID\tUsername\tBalance\tThreshold\tAddress");

  foreach ($users as $aUserData) {
    $aBalance = $transaction->getBalance($aUserData['id']);
    $dBalance = $aBalance['confirmed'];
    $log->logInfo("\t" . $aUserData['id'] . "\t" . $aUserData['username'] . "\t" . $dBalance . "\t" . $aUserData['ap_threshold'] . "\t\t" . $aUserData['coin_address']);

    // Only run if balance meets threshold and can pay the potential transaction fee
    if ($dBalance > $aUserData['ap_threshold'] && $dBalance > $config['txfee']) {
      // Validate address against RPC
      try {
        $aStatus = $bitcoin->validateaddress($aUserData['coin_address']);
        if (!$aStatus['isvalid']) {
          $log->logError('Failed to verify this users coin address, skipping payout');
          continue;
        }
      } catch (BitcoinClientException $e) {
        $log->logError('Failed to verifu this users coin address, skipping payout');
        continue;
      }

      // Send balance, fees are reduced later by RPC Server
      try {
        $bitcoin->sendtoaddress($aUserData['coin_address'], $dBalance - $config['txfee']);
      } catch (BitcoinClientException $e) {
        $log->logError('Failed to send requested balance to coin address, please check payout process');
        continue;
      }

      // Create transaction record
      if ($transaction->addTransaction($aUserData['id'], $dBalance - $config['txfee'], 'Debit_AP', NULL, $aUserData['coin_address']) && $transaction->addTransaction($aUserData['id'], $config['txfee'], 'TXFee', NULL, $aUserData['coin_address'])) {
        // Mark all older transactions as archived
        if (!$transaction->setArchived($aUserData['id'], $transaction->insert_id))
          $log->logError('Failed to mark transactions for user #' . $aUserData['id'] . ' prior to #' . $transaction->insert_id . ' as archived');
        // Notify user via  mail
        $aMailData['email'] = $user->getUserEmail($user->getUserName($aUserData['id']));
        $aMailData['subject'] = 'Auto Payout Completed';
        $aMailData['amount'] = $dBalance;
        if (!$notification->sendNotification($aUserData['id'], 'auto_payout', $aMailData))
          $log->logError('Failed to send notification email to users address: ' . $aMailData['email']);
      } else {
        $log->logError('Failed to add new Debit_AP transaction in database for user ' . $user->getUserName($aUserData['id']));
      }
    }
  }
} else {
  $log->logDebug("  no user has configured their AP > 0");
}

// Cron cleanup and monitoring
require_once('cron_end.inc.php');
?>
