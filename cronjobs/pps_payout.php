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


// Check if we are set as the payout system
if ($config['payout_system'] != 'pps') {
  $log->logInfo("\tPlease activate this cron in configuration via payout_system = pps\n");
  exit(0);
}
$log->logDebug('Starting PPS Payout');

// Fetch all transactions since our last block
if ( $bitcoin->can_connect() === true ){
  $dDifficulty = $bitcoin->getdifficulty();
  if (is_array($dDifficulty) && array_key_exists('proof-of-work', $dDifficulty))
    $dDifficulty = $dDifficulty['proof-of-work'];
} else {
  $log->logFatal('Unable to connect to RPC server backend');
  $monitoring->endCronjob($cron_name, 'E0006', 1, true);
}

// We support some dynamic reward targets but fall back to our fixed value
// Re-calculate after each run due to re-targets in this loop
// We don't use the classes implementation just in case people start mucking around with it
$strRewardType = $config['pps']['reward']['type'];
if ($config['pps']['reward']['type'] == 'blockavg' && $block->getBlockCount() > 0) {
  $pps_reward = round($block->getAvgBlockReward($config['pps']['blockavg']['blockcount']));
} else {
  if ($config['pps']['reward']['type'] == 'block') {
    if ($aLastBlock = $block->getLast()) {
      $pps_reward = $aLastBlock['amount'];
    } else {
      $pps_reward = $config['pps']['reward']['default'];
      $strRewardType = 'fixed';
    }
  } else {
    $pps_reward = $config['pps']['reward']['default'];
  }
}

// Per-share value to be paid out to users
$pps_value = round($coin->calcPPSValue($pps_reward, $dDifficulty), 12);

// Find our last share accounted and last inserted share for PPS calculations
if (!$iPreviousShareId = $setting->getValue('pps_last_share_id')) {
  $log->logError("Failed to fetch Previous Share ID. This is okay on your first run or when without any shares. ERROR: " . $setting->getCronError());
  $iPreviousShareId = 0;
}

if (!$iLastShareId = $share->getLastInsertedShareId()) {
  $log->logError("Failed to fetch Last Inserted PPS Share ID. ERROR: " . $share->getCronError());
}

if ($iPreviousShareId >= $iLastShareId) {
  $log->logInfo('No new shares to account for. Exiting until next run.');
  $monitoring->endCronjob($cron_name, 'E0080', 0, true, false);
}

// Check for all new shares, we start one higher as our last accounted share to avoid duplicates
$log->logInfo("\tQuery getSharesForAccounts... starting...");
if (!$aAccountShares = $share->getSharesForAccounts($iPreviousShareId, $iLastShareId)) {
  $log->logError("Failed to fetch Account Shares. ERROR: " . $share->getCronError());
}
$log->logInfo("\tQuery Completed...");

if (!empty($aAccountShares)) {
  // Runtime information for this payout
  $log->logInfo('Runtime information for this payout');
  $strLogMask = "| %-15.15s | %15.15s | %15.15s | %15.15s |";
  $log->logInfo(sprintf($strLogMask, 'PPS reward type', 'Reward Base', 'Difficulty', 'PPS Value'));
  $log->logInfo(sprintf($strLogMask, $strRewardType, $pps_reward, $dDifficulty, $pps_value));
  $log->logInfo('Per-user payout information');
  $strLogMask = "| %8.8s | %25.25s | %15.15s | %15.15s | %18.18s | %18.18s | %18.18s |";
  $log->logInfo(sprintf($strLogMask, 'User ID', 'Username', 'Invalid', 'Valid', '  *   PPS Value', '  =  Payout', 'Donation', 'Fee'));
}

foreach ($aAccountShares as $aData) {
  // Skip entries that have no account ID, user deleted?
  if (empty($aData['id'])) {
    $log->logInfo('User ' . $aData['username'] . ' does not have an associated account, skipping');
    continue;
  }

  // Payout for this user
  $aData['payout'] = round($aData['valid'] * $pps_value, 12);

  // Defaults
  $aData['fee' ] = 0;
  $aData['donation'] = 0;

  // Calculate block fees
  if ($config['fees'] > 0 && $aData['no_fees'] == 0)
    $aData['fee'] = round($config['fees'] / 100 * $aData['payout'], 12);
  // Calculate donation amount
  $aData['donation'] = round($user->getDonatePercent($user->getUserId($aData['username'])) / 100 * ( $aData['payout'] - $aData['fee']), 12);

  $log->logInfo(sprintf(
    $strLogMask, $aData['id'], $aData['username'], $aData['invalid'], $aData['valid'],
    number_format($pps_value, 12), number_format($aData['payout'], 12), number_format($aData['donation'], 12), number_format($aData['fee'], 12)
  ));

  // Add new credit transaction
  if (!$transaction->addTransaction($aData['id'], $aData['payout'], 'Credit_PPS'))
    $log->logError('Failed to add Credit_PPS transaction in database: ' . $transaction->getCronError());
  // Add new fee debit for this block
  if ($aData['fee'] > 0 && $config['fees'] > 0)
    if (!$transaction->addTransaction($aData['id'], $aData['fee'], 'Fee_PPS'))
      $log->logError('Failed to add Fee_PPS transaction in database: ' . $transaction->getCronError());
  // Add new donation debit
  if ($aData['donation'] > 0)
    if (!$transaction->addTransaction($aData['id'], $aData['donation'], 'Donation_PPS'))
      $log->logError('Failed to add Donation_PPS transaction in database: ' . $transaction->getCronError());
}

// Store our last inserted ID for the next run
$log->logInfo("\tStoring last paid share ID...");
if (!$setting->setValue('pps_last_share_id', $iLastShareId)) {
  $log->logError("Failed to store last paid share ID. ERROR: " . $setting->getCronError());
}

// Fetch all unaccounted blocks
$log->logInfo("\tFetching unaccounted blocks for round closure");
if ($aAllBlocks = $block->getAllUnaccounted('ASC')) {
  // Go through blocks and archive/delete shares that have been accounted for
  foreach ($aAllBlocks as $iIndex => $aBlock) {
    // If we are running through more than one block, check for previous share ID
    $log->logInfo("\tProcess each block for Previous Share ID.");
    $iLastBlockShare = @$aAllBlocks[$iIndex - 1]['share_id'] ? @$aAllBlocks[$iIndex - 1]['share_id'] : 0;
    if (!is_numeric($aBlock['share_id'])) {
      $log->logError("Block " . $aBlock['height'] . " has no share_id associated with it, not going to continue");
      $monitoring->endCronjob($cron_name, 'E0062', 0, true);
    }
    // Per account statistics
    $log->logInfo("\tRefresh user statistics...");
    if (!$aAccountShares = $share->getSharesForAccounts(@$iLastBlockShare, $aBlock['share_id'])) {
      $log->logError("Failed to Account Shares. ERROR: " . $share->getCronError());
    }
    foreach ($aAccountShares as $key => $aData) {
      if (!$statistics->updateShareStatistics($aData, $aBlock['id']))
        $log->logError("Failed to update statistics for Block " . $aBlock['id'] . "for" . $aData['username'] . ' ERROR: ' . $statistics->getCronError());
    }
    $log->logInfo("\tUser Statistics updated.");

    // Move shares to archive
    $log->logInfo("\tBlock: " . $aBlock['id'] . "\t Move shares to archive...");
    if ($aBlock['share_id'] < $iLastShareId) {
      if (!$share->moveArchive($aBlock['share_id'], $aBlock['id'], @$iLastBlockShare))
        $log->logError("Failed to copy shares to from " . $aBlock['share_id'] . " to " . $iLastBlockShare . ' Error: ' . $share->getCronError());
    }
    $log->logInfo("\tBlock: " . $aBlock['id'] . "\t Shares moved to archive...");

    // Delete shares
    $log->logInfo("\tBlock: " . $aBlock['id'] . "\t Deleting accounted shares...");
    if ($aBlock['share_id'] < $iLastShareId && !$share->deleteAccountedShares($aBlock['share_id'], $iLastBlockShare)) {
      $log->logFatal("Failed to delete accounted shares from " . $aBlock['share_id'] . " to " . $iLastBlockShare . ", aborting! Error: " . $share->getCronError());
      $monitoring->endCronjob($cron_name, 'E0016', 1, true);
    }
    $log->logInfo("\tBlock: " . $aBlock['id'] . "\t Deleted accounted shares.");

    // Mark this block as accounted for
    $log->logInfo("\tBlock: " . $aBlock['id'] . "\t Marking Block as accounted...");
    if (!$block->setAccounted($aBlock['id'])) {
      $log->logFatal("Failed to mark block as accounted! Aborting! Error: " . $block->getCronError());
      $monitoring->endCronjob($cron_name, 'E0014', 1, true);
    }
    $log->logInfo("\tBlock: " . $aBlock['id'] . "\t Block paid and accounted for.");
  }
} else if (empty($aAllBlocks)) {
  $log->logInfo("\tNo new blocks.");
  // No monitoring event here, not fatal for PPS
} else {
  $log->logInfo("Failed to fetch unaccounted Blocks. NOTICE: " . $block->getCronError());
}
$log->logInfo("Completed PPS Payout");

require_once('cron_end.inc.php');
?>
