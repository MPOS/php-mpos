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
  $log->logFatal('Unable to connect to RPC server backend');
  $monitoring->endCronjob($cron_name, 'E0006', 1, true);
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
        $log->logFatal('Unable to add block: ' . $aData['height'] . ': ' . $block->getCronError());
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
  $log->logInfo("Block ID\tHeight\t\tAmount\tShare ID\tShares\tFinder\tWorker\t\tType");
  foreach ($aAllBlocks as $iIndex => $aBlock) {
    if (empty($aBlock['share_id'])) {
      // Fetch share information
      if ( !$iPreviousShareId = $block->getLastShareId())
        $iPreviousShareId = 0;
      // Fetch this blocks upstream ID
      $aBlockRPCInfo = $bitcoin->query('getblock', $aBlock['blockhash']);
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
        $monitoring->endCronjob($cron_name, 'E0005', 1, true);
      }

      $log->logInfo(
        $aBlock['id'] . "\t\t"
        . $aBlock['height'] . "\t\t"
        . $aBlock['amount'] . "\t"
        . $iCurrentUpstreamId . "\t\t"
        . $iRoundShares . "\t"
        . "[$iAccountId] " . $user->getUserName($iAccountId) . "\t"
        . $iWorker . "\t"
        . $share->share_type
      );

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
