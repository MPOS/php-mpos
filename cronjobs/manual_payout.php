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
  $log->logInfo(" manual payout disabled via admin panel");
  $monitoring->endCronjob($cron_name, 'E0009', 0, true);
}

if ($bitcoin->can_connect() !== true) {
  $log->logFatal(" unable to connect to RPC server, exiting");
  $monitoring->endCronjob($cron_name, 'E0006', 1, true);
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
    if ($dBalance > $config['txfee_manual']) {
      // To ensure we don't run this transaction again, lets mark it completed
      if (!$oPayout->setProcessed($aData['id'])) {
        $log->logFatal('unable to mark transactions ' . $aData['id'] . ' as processed.');
        $monitoring->endCronjob($cron_name, 'E0010', 1, true);
      }

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
        $txid = $bitcoin->sendtoaddress($aData['coin_address'], $dBalance - $config['txfee_manual']);
      } catch (BitcoinClientException $e) {
        $log->logError('Failed to send requested balance to coin address, please check payout process');
        continue;
      }

      if ($transaction->addTransaction($aData['account_id'], $dBalance - $config['txfee_manual'], 'Debit_MP', NULL, $aData['coin_address'], $txid) && $transaction->addTransaction($aData['account_id'], $config['txfee_manual'], 'TXFee', NULL, $aData['coin_address'])) {
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
        $log->logFatal('Failed to add new Debit_MP transaction in database for user ' . $user->getUserName($aData['account_id']));
        $monitoring->endCronjob($cron_name, 'E0064', 1, true);
      }
    }

  }
}

require_once('cron_end.inc.php');
?>
