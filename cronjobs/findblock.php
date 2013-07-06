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

// Include all settings and classes
require_once('shared.inc.php');

// Fetch our last block found from the DB as a starting point
$aLastBlock = @$block->getLast();
$strLastBlockHash = $aLastBlock['blockhash'];
if (!$strLastBlockHash) { 
  $strLastBlockHash = '';
}

// Fetch all transactions since our last block
if ( $bitcoin->can_connect() === true ){
  $aTransactions = $bitcoin->query('listsinceblock', $strLastBlockHash);
} else {
  verbose("Aborted: " . $bitcoin->can_connect() . "\n");
  exit(1);
}

// Nothing to do so bail out
if (empty($aTransactions['transactions'])) {
  verbose("No new RPC transactions since last block\n");
} else {
  // Table header
  verbose("Blockhash\t\tHeight\tAmount\tConfirmations\tDiff\t\tTime\t\t\tStatus\n");

  // Let us add those blocks as unaccounted
  foreach ($aTransactions['transactions'] as $iIndex => $aData) {
    if ( $aData['category'] == 'generate' || $aData['category'] == 'immature' ) {
      $aBlockInfo = $bitcoin->query('getblock', $aData['blockhash']);
      $config['reward_type'] == 'block' ? $aData['amount'] = $aData['amount'] : $aData['amount'] = $config['reward'];
      $aData['height'] = $aBlockInfo['height'];
      $aData['difficulty'] = $aBlockInfo['difficulty'];
      verbose(substr($aData['blockhash'], 0, 15) . "...\t" .
        $aData['height'] . "\t" .
        $aData['amount'] . "\t" .
        $aData['confirmations'] . "\t\t" .
        $aData['difficulty'] . "\t" .
        strftime("%Y-%m-%d %H:%M:%S", $aData['time']) . "\t");
      if ( $block->addBlock($aData) ) {
        verbose("Added\n");
      } else {
        verbose("Failed" . "\n");
      }
    }
  }
}

verbose("\n");
// Now with our blocks added we can scan for their upstream shares
$aAllBlocks = $block->getAllUnaccounted('ASC');
if (empty($aAllBlocks)) {
    verbose("No new unaccounted blocks found\n");
} else {
  // Loop through our unaccounted blocks
  verbose("\nBlock ID\tBlock Height\tAmount\tShare ID\tShares\tFinder\t\t\tStatus\n");
  foreach ($aAllBlocks as $iIndex => $aBlock) {
    if (empty($aBlock['share_id'])) {
      // Fetch this blocks upstream ID
      if ($share->setUpstream($block->getLastUpstreamId())) {
        $iCurrentUpstreamId = $share->getUpstreamId();
        $iAccountId = $user->getUserId($share->getUpstreamFinder());
      } else {
        verbose("\nUnable to fetch blocks upstream share. Aborting!\n");
        verbose($share->getError() . "\n");
        exit;
      }

      // Fetch share information
      if (!$iPreviousShareId = $block->getLastShareId()) {
        $iPreviousShareId = 0;
        verbose("\nUnable to find highest share ID found so far\n");
        verbose("If this is your first block, this is normal\n\n");
      }
      $iRoundShares = $share->getRoundShares($iPreviousShareId, $iCurrentUpstreamId);

      // Store new information
      $strStatus = "OK";
      if (!$block->setShareId($aBlock['id'], $iCurrentUpstreamId))
        $strStatus = "Share ID Failed";
      if (!$block->setFinder($aBlock['id'], $iAccountId))
        $strStatus = "Finder Failed";
      if (!$block->setShares($aBlock['id'], $iRoundShares))
        $strStatus = "Shares Failed";
      if ($config['block_bonus'] > 0 && !$transaction->addTransaction($iAccountId, $config['block_bonus'], 'Bonus', $aBlock['id'])) {
        $strStatus = "Bonus Failed";
      }

      verbose(
        $aBlock['id'] . "\t\t"
        . $aBlock['height'] . "\t\t"
        . $aBlock['amount'] . "\t"
        . $iCurrentUpstreamId . "\t\t"
        . $iRoundShares . "\t"
        . "[$iAccountId] " . $user->getUserName($iAccountId) . "\t\t"
        . $strStatus
        . "\n"
      );

      // Notify users
      $aAccounts = $notification->getNotificationAccountIdByType('new_block');
      if (is_array($aAccounts)) {
        foreach ($aAccounts as $aData) {
          $aMailData['height'] = $aBlock['height'];
          $aMailData['subject'] = 'New Block';
          $aMailData['email'] = $user->getUserEmail($user->getUserName($aData['account_id']));
          $aMailData['shares'] = $iRoundShares;
          $notification->sendNotification($aData['account_id'], 'new_block', $aMailData);
        }
      }
    }
  }
}
?>

