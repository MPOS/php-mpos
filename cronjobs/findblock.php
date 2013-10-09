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
      $aBlockInfo = $bitcoin->query('getblock', $aData['blockhash']);
      $config['reward_type'] == 'block' ? $aData['amount'] = $aData['amount'] : $aData['amount'] = $config['reward'];
      $aData['height'] = $aBlockInfo['height'];
      $aData['difficulty'] = $aBlockInfo['difficulty'];
      $log->logInfo(substr($aData['blockhash'], 0, 15) . "...\t" .
        $aData['height'] . "\t" .
        $aData['amount'] . "\t" .
        $aData['confirmations'] . "\t\t" .
        $aData['difficulty'] . "\t" .
        strftime("%Y-%m-%d %H:%M:%S", $aData['time']));
      if ( ! empty($aBlockInfo['flags']) && preg_match('/proof-of-stake/', $aBlockInfo['flags']) ) {
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
  $log->logInfo("Block ID\t\tHeight\tAmount\tShare ID\tShares\tFinder\tType");
  foreach ($aAllBlocks as $iIndex => $aBlock) {
    if (empty($aBlock['share_id'])) {
      // Fetch this blocks upstream ID
      $aBlockInfo = $bitcoin->query('getblock', $aBlock['blockhash']);
      if ($share->setUpstream($aBlockInfo, $block->getLastUpstreamId())) {
        $iCurrentUpstreamId = $share->getUpstreamId();
        $iAccountId = $user->getUserId($share->getUpstreamFinder());
      } else {
        $log->logFatal('Unable to fetch blocks upstream share, aborted:' . $share->getError());
        $monitoring->setStatus($cron_name . "_active", "yesno", 0); 
        $monitoring->setStatus($cron_name . "_message", "message", "Unable to fetch blocks " . $aBlock['height'] . " upstream share: " . $share->getError());
        $monitoring->setStatus($cron_name . "_status", "okerror", 1); 
        exit;
      }

      // Fetch share information
      if (!$iPreviousShareId = $block->getLastShareId()) {
        $iPreviousShareId = 0;
        $log->logInfo('Unable to find highest share ID found so far, if this is your first block, this is normal.');
      }
      $iRoundShares = $share->getRoundShares($iPreviousShareId, $iCurrentUpstreamId);

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

      $log->logInfo(
        $aBlock['id'] . "\t\t"
        . $aBlock['height'] . "\t\t"
        . $aBlock['amount'] . "\t"
        . $iCurrentUpstreamId . "\t\t"
        . $iRoundShares . "\t"
        . "[$iAccountId] " . $user->getUserName($iAccountId) . "\t"
        . $share->share_type
      );

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
