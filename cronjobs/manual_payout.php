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

if ($setting->getValue('disable_mp') == 1) {
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

// Fetch outstanding payout requests
$aPayouts = $oPayout->getUnprocessedPayouts();

if (count($aPayouts) > 0) {
  $log->logInfo("\tAccount ID\tUsername\tBalance\t\tCoin Address");
  foreach ($aPayouts as $aData) {
    $aBalance = $transaction->getBalance($aData['account_id']);
    $dBalance = $aBalance['confirmed'];
    $aData['coin_address'] = $user->getCoinAddress($aData['account_id']);
    $aData['username'] = $user->getUserName($aData['account_id']);
    if ($dBalance > $config['txfee']) {
      $log->logInfo("\t" . $aData['account_id'] . "\t\t" . $aData['username'] . "\t" . $dBalance . "\t\t" . $aData['coin_address']);
      try {
        $aStatus = $bitcoin->validateaddress($aData['coin_address']);
        if (!$aStatus['isvalid']) {
          $log->logError('Failed to verify this users coin address, skipping payout');
          continue;
        }
      } catch (BitcoinClientException $e) {
        $log->logError('Failed to verify this users coin address, skipping payout');
        continue;
      }
      try {
        $bitcoin->sendtoaddress($aData['coin_address'], $dBalance - $config['txfee']);
      } catch (BitcoinClientException $e) {
        $log->logError('Failed to send requested balance to coin address, please check payout process');
        continue;
      }
      // To ensure we don't run this transaction again, lets mark it completed
      if (!$oPayout->setProcessed($aData['id'])) {
        $log->logFatal('unable to mark transactions ' . $aData['id'] . ' as processed.');
        $monitoring->setStatus($cron_name . "_active", "yesno", 0);
        $monitoring->setStatus($cron_name . "_message", "message", "Unable set payout as processed");
        $monitoring->setStatus($cron_name . "_status", "okerror", 1);
        exit(1);
      }

      if ($transaction->addTransaction($aData['account_id'], $dBalance - $config['txfee'], 'Debit_MP', NULL, $aData['coin_address']) && $transaction->addTransaction($aData['account_id'], $config['txfee'], 'TXFee', NULL, $aData['coin_address'])) {
        // Mark all older transactions as archived
        if (!$transaction->setArchived($aData['account_id'], $transaction->insert_id))
          $log->logError('Failed to mark transactions for #' . $aData['account_id'] . ' prior to #' . $transaction->insert_id . ' as archived');
        // Notify user via  mail
        $aMailData['email'] = $user->getUserEmail($user->getUserName($aData['account_id']));
        $aMailData['subject'] = 'Manual Payout Completed';
        $aMailData['amount'] = $dBalance;
        $aMailData['payout_id'] = $aData['id'];
        if (!$notification->sendNotification($aData['account_id'], 'manual_payout', $aMailData))
          $log->logError('Failed to send notification email to users address: ' . $aMailData['email']);
      } else {
        $log->logError('Failed to add new Debit_MP transaction in database for user ' . $user->getUserName($aData['account_id']));
      }
    }

  }
}

require_once('cron_end.inc.php');
?>
