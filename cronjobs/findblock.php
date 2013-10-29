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

// Fetch our last block found from the DB as a starting point
$aLastBlock = @$block->getLast();
$strLastBlockHash = $aLastBlock['blockhash'];
if (!$strLastBlockHash) $strLastBlockHash = '';

// Fetch all transactions since our last block
if ( $bitcoin->can_connect() === true ){
  $aTransactions = $bitcoin->query('listsinceblock', $strLastBlockHash);
} else {
  $log->logFatal('Unable to conenct to RPC server backend');
  $monitoring->setStatus($cron_name . "_active", "yesno", 0); 
  $monitoring->setStatus($cron_name . "_message", "message", "Unable to connect to RPC server");
  $monitoring->setStatus($cron_name . "_status", "okerror", 1); 
  exit(1);
}

// Nothing to do so bail out
if (empty($aTransactions['transactions'])) {
  $log->logDebug('No new RPC transactions since last block');
} else {
  // Table header
  $log->logInfo("Blockhash\t\tHeight\tAmount\tConfirmations\tDiff\t\tTime");

  // Let us add those blocks as unaccounted
  foreach ($aTransactions['transactions'] as $iIndex => $aData) {
    if ( $aData['category'] == 'generate' || $aData['category'] == 'immature' ) {
      $aBlockRPCInfo = $bitcoin->query('getblock', $aData['blockhash']);
      $config['reward_type'] == 'block' ? $aData['amount'] = $aData['amount'] : $aData['amount'] = $config['reward'];
      $aData['height'] = $aBlockRPCInfo['height'];
      $aData['difficulty'] = $aBlockRPCInfo['difficulty'];
      $log->logInfo(substr($aData['blockhash'], 0, 15) . "...\t" .
        $aData['height'] . "\t" .
        $aData['amount'] . "\t" .
        $aData['confirmations'] . "\t\t" .
        $aData['difficulty'] . "\t" .
        strftime("%Y-%m-%d %H:%M:%S", $aData['time']));
      if ( ! empty($aBlockRPCInfo['flags']) && preg_match('/proof-of-stake/', $aBlockRPCInfo['flags']) ) {
        $log->logInfo("Block above with height " .  $aData['height'] . " not added to database, proof-of-stake block!");
        continue;
      }
      if (!$block->addBlock($aData) ) {
        $log->logFatal('Unable to add this block to database: ' . $aData['height']);
      }
    }
  }
}

// Now with our blocks added we can scan for their upstream shares
$aAllBlocks = $block->getAllUnsetShareId('ASC');
if (empty($aAllBlocks)) {
  $log->logDebug('No new blocks without share_id found in database');
} else {
  // Loop through our unaccounted blocks
  $log->logInfo("Block ID\tHeight\t\tAmount\tShare ID\tShares\tFinder\t\tType");
  foreach ($aAllBlocks as $iIndex => $aBlock) {
    if (empty($aBlock['share_id'])) {
      // Fetch share information
      $iPreviousShareId = $block->getLastShareId();
      if ( !$iPreviousShareId && $block->getBlockCount() > 1) {
        $iPreviousShareId = 0;
        // $log->logError('Unable to find highest share ID found so far, assuming share ID 0 as previous found upstream share.');
      }

      // Fetch this blocks upstream ID
      $aBlockRPCInfo = $bitcoin->query('getblock', $aBlock['blockhash']);
      if ($share->findUpstreamShare($aBlockRPCInfo, $iPreviousShareId)) {
        $iCurrentUpstreamId = $share->getUpstreamShareId();
        // Out of order share detection
        if ($iCurrentUpstreamId < $iPreviousShareId) {
          // Fetch our offending block
          $aBlockError = $block->getBlockByShareId($iPreviousShareId);
          $log->logError('E0001: The block with height ' . $aBlock['height'] . ' found share ' . $iCurrentUpstreamId . ' which is < than ' . $iPreviousShareId . ' of block ' . $aBlockError['height'] . '.');
          if ( !$aShareError = $share->getShareById($aBlockError['share_id']) || !$aShareCurrent = $share->getShareById($iCurrentUpstreamId)) {
            // We were not able to fetch all shares that were causing this detection to trigger, bail out
            $log->logFatal('E0002: Failed to fetch both offending shares ' . $iCurrentUpstreamId . ' and ' . $iPreviousShareId . '. Block height: ' . $aBlock['height']);
            $monitoring->setStatus($cron_name . "_active", "yesno", 0);
            $monitoring->setStatus($cron_name . "_message", "message", "E0002: Upstream shares not found");
            $monitoring->setStatus($cron_name . "_status", "okerror", 1);
            exit(1);
          }
          // Shares seem to be out of order, so lets change them
          if ( !$share->updateShareById($iCurrentUpstreamId, $aShareError) || !$share->updateShareById($iPreviousShareId, $aShareCurrent)) {
            // We couldn't update one of the shares! That might mean they have been deleted already
            $log->logFatal('E0003: Failed to change shares order!');
            $monitoring->setStatus($cron_name . "_active", "yesno", 0);
            $monitoring->setStatus($cron_name . "_message", "message", "E0003: Failed share update");
            $monitoring->setStatus($cron_name . "_status", "okerror", 1);
            exit(1);
          }
          // Reset our offending block so the next run re-checks the shares
          if (!$block->setShareId($aBlockError['id'], NULL) && !$block->setFinder($aBlockError['id'], NULL) || !$block->setShares($aBlockError['id'], NULL)) {
            $log->logFatal('E0004: Failed to reset previous block: ' . $aBlockError['height']);
            $log->logError('Failed to reset block in database: ' . $aBlockError['height']);
            $monitoring->setStatus($cron_name . "_active", "yesno", 0);
            $monitoring->setStatus($cron_name . "_message", "message", "E0004: Failed to reset block");
            $monitoring->setStatus($cron_name . "_status", "okerror", 1);
            exit(1);
          }
          $monitoring->setStatus($cron_name . "_active", "yesno", 0);
          $monitoring->setStatus($cron_name . "_message", "message", "Out of Order Share detected, autofixed");
          $monitoring->setStatus($cron_name . "_status", "okerror", 1);
          exit(0);
        } else {
          $iRoundShares = $share->getRoundShares($iPreviousShareId, $iCurrentUpstreamId);
          $iAccountId = $user->getUserId($share->getUpstreamFinder());
        }
      } else {
        $log->logFatal('E0005: Unable to fetch blocks upstream share, aborted:' . $share->getError());
        $monitoring->setStatus($cron_name . "_active", "yesno", 0);
        $monitoring->setStatus($cron_name . "_message", "message", "Unable to fetch blocks " . $aBlock['height'] . " upstream share: " . $share->getError());
        $monitoring->setStatus($cron_name . "_status", "okerror", 1);
        exit(1);
      }

      $log->logInfo(
        $aBlock['id'] . "\t\t"
        . $aBlock['height'] . "\t\t"
        . $aBlock['amount'] . "\t"
        . $iCurrentUpstreamId . "\t\t"
        . $iRoundShares . "\t"
        . "[$iAccountId] " . $user->getUserName($iAccountId) . "\t"
        . $share->share_type
      );

      // Store new information
      if (!$block->setShareId($aBlock['id'], $iCurrentUpstreamId))
        $log->logError('Failed to update share ID in database for block ' . $aBlock['height']);
      if (!$block->setFinder($aBlock['id'], $iAccountId))
        $log->logError('Failed to update finder account ID in database for block ' . $aBlock['height']);
      if (!$block->setShares($aBlock['id'], $iRoundShares))
        $log->logError('Failed to update share count in database for block ' . $aBlock['height']);
      if ($config['block_bonus'] > 0 && !$transaction->addTransaction($iAccountId, $config['block_bonus'], 'Bonus', $aBlock['id'])) {
        $log->logError('Failed to create Bonus transaction in database for user ' . $user->getUserName($iAccountId) . ' for block ' . $aBlock['height']);
      }

      if ($setting->getValue('disable_notifications') != 1) {
        // Notify users
        $aAccounts = $notification->getNotificationAccountIdByType('new_block');
        if (is_array($aAccounts)) {
          foreach ($aAccounts as $aData) {
            $aMailData['height'] = $aBlock['height'];
            $aMailData['subject'] = 'New Block';
            $aMailData['email'] = $user->getUserEmail($user->getUserName($aData['account_id']));
            $aMailData['shares'] = $iRoundShares;
            if (!$notification->sendNotification($aData['account_id'], 'new_block', $aMailData))
              $log->logError('Failed to notify user of new found block: ' . $user->getUserName($aData['account_id']));
          }
        }
      }
    }
  }
}

require_once('cron_end.inc.php');
?>
