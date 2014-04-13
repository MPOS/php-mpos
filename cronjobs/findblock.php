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
$aLastBlock = @$block->getLastValid();
$strLastBlockHash = $aLastBlock['blockhash'];
if (!$strLastBlockHash) $strLastBlockHash = '';

// Fetch all transactions since our last block
if ( $bitcoin->can_connect() === true ){
  $aTransactions = $bitcoin->listsinceblock($strLastBlockHash);
} else {
  $log->logFatal('Unable to connect to RPC server backend');
  $monitoring->endCronjob($cron_name, 'E0006', 1, true);
}

// Nothing to do so bail out
if (empty($aTransactions['transactions'])) {
  $log->logDebug('No new RPC transactions since last block');
} else {
  $header = false;
  // Let us add those blocks as unaccounted
  foreach ($aTransactions['transactions'] as $iIndex => $aData) {
    if ( $aData['category'] == 'generate' || $aData['category'] == 'immature' ) {
      // Table header, printe once if we found a block
      $strLogMask = "| %-20.20s | %15.15s | %10.10s | %13.13s | %25.25s | %18.18s |";
      // Loop through our unaccounted blocks
      if (!$header) {
        $log->logInfo('Starting RPC block detecion, blocks are stored in Database');
        $log->logInfo(sprintf($strLogMask, 'Blockhash', 'Height', 'Amount', 'Confirmations', 'Difficulty', 'Time'));
        $header = true;
      }

      $aBlockRPCInfo = $bitcoin->getblock($aData['blockhash']);
      $config['reward_type'] == 'block' ? $aData['amount'] = $aData['amount'] : $aData['amount'] = $config['reward'];
      $aData['height'] = $aBlockRPCInfo['height'];
      $aTxDetails = $bitcoin->gettransaction($aBlockRPCInfo['tx'][0]);
      if (!isset($aBlockRPCInfo['confirmations'])) {
        $aData['confirmations'] = $aBlockRPCInfo['confirmations'];
      } else if (isset($aTxDetails['confirmations'])) {
        $aData['confirmations'] = $aTxDetails['confirmations'];
      } else {
        $log->logFatal('    RPC does not return any usable block confirmation information');
        $monitoring->endCronjob($cron_name, 'E0082', 1, true);
      }
      $aData['difficulty'] = $aBlockRPCInfo['difficulty'];
      $log->logInfo(sprintf($strLogMask, substr($aData['blockhash'], 0, 17)."...", $aData['height'], $aData['amount'], $aData['confirmations'], $aData['difficulty'], strftime("%Y-%m-%d %H:%M:%S", $aData['time'])));
      if ( ! empty($aBlockRPCInfo['flags']) && preg_match('/proof-of-stake/', $aBlockRPCInfo['flags']) ) {
        $log->logInfo("Block above with height " .  $aData['height'] . " not added to database, proof-of-stake block!");
        continue;
      }
      if (!$block->addBlock($aData) ) {
        $log->logFatal('Unable to add block: (' . $aData['height'] . ') ' . $aData['blockhash'] . ': ' . $block->getCronError());
        $monitoring->endCronjob($cron_name, 'E0081', 1, true);
      }
    }
  }
}

// Now with our blocks added we can scan for their upstream shares
$aAllBlocks = $block->getAllUnsetShareId('ASC');
if (empty($aAllBlocks)) {
  $log->logDebug('No new blocks without share_id found in database');
} else {
  $log->logInfo('Starting block share detection, this may take a while');
  $strLogMask = "| %8.8s | %10.10s | %15.15s | %10.10s | %25.25s | %-15.15s | %-15.15s | %18.18s |";
  // Loop through our unaccounted blocks
  $log->logInfo(sprintf($strLogMask, 'Block ID', 'Height', 'Amount', 'Share ID', 'Shares', 'Finder', 'Worker', 'Type'));
  foreach ($aAllBlocks as $iIndex => $aBlock) {
    if (empty($aBlock['share_id'])) {
      // Fetch share information
      if ( !$iPreviousShareId = $block->getLastShareId())
        $iPreviousShareId = 0;
      // Fetch this blocks upstream ID
      $aBlockRPCInfo = $bitcoin->getblock($aBlock['blockhash']);
      if ($share->findUpstreamShare($aBlockRPCInfo, $iPreviousShareId)) {
        $iCurrentUpstreamId = $share->getUpstreamShareId();
        // Rarely happens, but did happen once to me
        if ($iCurrentUpstreamId == $iPreviousShareId) {
          $log->logFatal($share->getErrorMsg('E0063'));
          $monitoring->endCronjob($cron_name, 'E0063', 1, true);
        }
        // Out of order share detection
        if ($iCurrentUpstreamId < $iPreviousShareId) {
          // Fetch our offending block
          $aBlockError = $block->getBlockByShareId($iPreviousShareId);
          $log->logError('E0001: The block with height ' . $aBlock['height'] . ' found share ' . $iCurrentUpstreamId . ' which is < than ' . $iPreviousShareId . ' of block ' . $aBlockError['height'] . '.');
          if ( !($aShareError = $share->getShareById($aBlockError['share_id'])) || !($aShareCurrent = $share->getShareById($iCurrentUpstreamId))) {
            // We were not able to fetch all shares that were causing this detection to trigger, bail out
            $log->logFatal('E0002: Failed to fetch both offending shares ' . $iCurrentUpstreamId . ' and ' . $iPreviousShareId . '. Block height: ' . $aBlock['height']);
            $monitoring->endCronjob($cron_name, 'E0002', 1, true);
          }
          // Shares seem to be out of order, so lets change them
          if ( !$share->updateShareById($iCurrentUpstreamId, $aShareError) || !$share->updateShareById($iPreviousShareId, $aShareCurrent)) {
            // We couldn't update one of the shares! That might mean they have been deleted already
            $log->logFatal('E0003: Failed to change shares order: ' . $share->getCronError());
            $monitoring->endCronjob($cron_name, 'E0003', 1, true);
          }
          // Reset our offending block so the next run re-checks the shares
          if (!$block->setShareId($aBlockError['id'], NULL) && !$block->setFinder($aBlockError['id'], NULL) || !$block->setShares($aBlockError['id'], NULL)) {
            $log->logFatal('E0004: Failed to reset previous block: ' . $aBlockError['height']);
            $log->logError('Failed to reset block in database: ' . $aBlockError['height']);
            $monitoring->endCronjob($cron_name, 'E0004', 1, true);
          }
          $monitoring->endCronjob($cron_name, 'E0007', 0, true);
        } else {
          $iRoundShares = $share->getRoundShares($iPreviousShareId, $iCurrentUpstreamId);
          $iAccountId = $user->getUserId($share->getUpstreamFinder());
          $iWorker = $share->getUpstreamWorker();
        }
      } else {
        $log->logFatal('E0005: Unable to fetch blocks upstream share, aborted:' . $share->getCronError());
        $monitoring->endCronjob($cron_name, 'E0005', 0, true);
      }

      // Print formatted row
      $log->logInfo(sprintf($strLogMask, $aBlock['id'], $aBlock['height'], $aBlock['amount'], $iCurrentUpstreamId, $iRoundShares, "[$iAccountId] " . $user->getUserName($iAccountId), $iWorker, $share->share_type));

      // Store new information
      if (!$block->setShareId($aBlock['id'], $iCurrentUpstreamId))
        $log->logError('Failed to update share ID in database for block ' . $aBlock['height'] . ': ' . $block->getCronError());
      if (!empty($iAccountId) && !$block->setFinder($aBlock['id'], $iAccountId))
        $log->logError('Failed to update finder account ID in database for block ' . $aBlock['height'] . ': ' . $block->getCronError());
      if (!$block->setFindingWorker($aBlock['id'], $iWorker))
        $log->logError('Failed to update worker ID in database for block ' . $aBlock['height'] . ': ' . $block->getCronError());
      if (!$block->setShares($aBlock['id'], $iRoundShares))
        $log->logError('Failed to update share count in database for block ' . $aBlock['height'] . ': ' . $block->getCronError());
      if ($config['block_bonus'] > 0 && !empty($iAccountId) && !$transaction->addTransaction($iAccountId, $config['block_bonus'], 'Bonus', $aBlock['id'])) {
        $log->logError('Failed to create Bonus transaction in database for user ' . $user->getUserName($iAccountId) . ' for block ' . $aBlock['height'] . ': ' . $transaction->getCronError());
      }

      if ($setting->getValue('disable_notifications') != 1 && $setting->getValue('notifications_disable_block') != 1) {
        // Notify users
        $aAccounts = $notification->getNotificationAccountIdByType('new_block');
        if (is_array($aAccounts)) {
		
          $finder = $user->getUserName($iAccountId);
          foreach ($aAccounts as $aData) {
            $aMailData['height'] = $aBlock['height'];
            $aMailData['subject'] = 'New Block';
            $aMailData['email'] = $user->getUserEmail($user->getUserName($aData['account_id']));
            $aMailData['shares'] = $iRoundShares;
            $aMailData['amount'] = $aBlock['amount'];
            $aMailData['difficulty'] = $aBlock['difficulty'];
            $aMailData['finder'] = $finder;
            $aMailData['currency'] = $config['currency'];
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
