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
$log->logInfo("Starting PPS Payout...");

// Fetch all transactions since our last block
if ( $bitcoin->can_connect() === true ){
  $dDifficulty = $bitcoin->getdifficulty();
  if (is_array($dDifficulty) && array_key_exists('proof-of-work', $dDifficulty))
    $dDifficulty = $dDifficulty['proof-of-work'];
} else {
  $log->logFatal("Aborted: " . $bitcoin->can_connect() . "\n");
  $monitoring->setStatus($cron_name . "_active", "yesno", 0); 
  $monitoring->setStatus($cron_name . "_message", "message", "Unable to connect to RPC server");
  $monitoring->setStatus($cron_name . "_status", "okerror", 1); 
  exit(1);
}

// We support some dynamic reward targets but fall back to our fixed value
// Re-calculate after each run due to re-targets in this loop
// We don't use the classes implementation just in case people start mucking around with it
if ($config['pps']['reward']['type'] == 'blockavg' && $block->getBlockCount() > 0) {
  $pps_reward = round($block->getAvgBlockReward($config['pps']['blockavg']['blockcount']));
  $log->logInfo("\tPPS reward using block average, amount: " . $pps_reward . "\tdifficulty: " . $dDifficulty);
} else {
  if ($config['pps']['reward']['type'] == 'block') {
    if ($aLastBlock = $block->getLast()) {
      $pps_reward = $aLastBlock['amount'];
      $log->logInfo("\tPPS value (Last Block): " . $pps_reward);
    } else {
      $pps_reward = $config['pps']['reward']['default'];
      $log->logInfo("\tPPS value (Default): " . $pps_reward);
    }
  } else {
    $pps_reward = $config['pps']['reward']['default'];
    $log->logInfo("\tPPS value (Default): " . $pps_reward);
  }
}

// Per-share value to be paid out to users
$pps_value = round($pps_reward / (pow(2, $config['target_bits']) * $dDifficulty), 12);
$log->logInfo("\tPPS value: " . $pps_value);

// Find our last share accounted and last inserted share for PPS calculations

if (!$iPreviousShareId = $setting->getValue('pps_last_share_id')) {
  $log->logError("Failed to fetch Previous Share ID. ERROR: " . $setting->getCronError());
}
$log->logInfo("\tPPS Last Share ID: " . $iPreviousShareId); 

if (!$iLastShareId = $share->getLastInsertedShareId()) {
  $log->logError("Failed to fetch Last Inserted PPS Share ID. ERROR: " . $share->getCronError());
}
$log->logInfo("\tPPS Last Processed Share ID: " . $iLastShareId);

// Check for all new shares, we start one higher as our last accounted share to avoid duplicates
$log->logInfo("\tQuery getSharesForAccounts... starting...");
if (!$aAccountShares = $share->getSharesForAccounts($iPreviousShareId + 1, $iLastShareId)) {
  $log->logError("Failed to fetch Account Shares. ERROR: " . $share->getCronError());
}
$log->logInfo("\tQuery Completed...");

if (!empty($aAccountShares)) {
  // Info for this payout
  $log->logInfo("\tPPS reward type: " . $config['pps']['reward']['type'] . ", amount: " . $pps_reward . "\tdifficulty: " . $dDifficulty . "\tPPS value: " . $pps_value);
  $log->logInfo("\tRunning through accounts to process shares...");
  $log->logInfo("\tID\tUsername\tInvalid\tValid\t\tPPS Value\t\tPayout\t\tDonation\tFee");
}

foreach ($aAccountShares as $aData) {
  // Skip entries that have no account ID, user deleted?
  if (empty($aData['id'])) {
    $log->logInfo('User ' . $aData['username'] . ' does not have an associated account, skipping');
    continue;
  }

  // MPOS uses a base difficulty setting to avoid showing weightened shares
  // Since we need weightened shares here, we go back to the proper value for payouts
  $aData['payout'] = round($aData['valid'] * pow(2, ($config['difficulty'] - 16)) * $pps_value, 8);

  // Defaults
  $aData['fee' ] = 0;
  $aData['donation'] = 0;

  // Calculate block fees
  if ($config['fees'] > 0 && $aData['no_fees'] == 0)
    $aData['fee'] = round($config['fees'] / 100 * $aData['payout'], 8);
  // Calculate donation amount
  $aData['donation'] = round($user->getDonatePercent($user->getUserId($aData['username'])) / 100 * ( $aData['payout'] - $aData['fee']), 8);

  $log->logInfo($aData['id'] . "\t" .
    $aData['username'] . "\t" .
    $aData['invalid'] . "\t" .
    $aData['valid'] * pow(2, ($config['difficulty'] - 16)) . "\t*\t" .
    number_format($pps_value, 12) . "\t=\t" .
    number_format($aData['payout'], 8) . "\t" .
    number_format($aData['donation'], 8) . "\t" .
    number_format($aData['fee'], 8));

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
$log->logInfo("\tFetching Last Share ID...");
if (!$setting->setValue('pps_last_share_id', $iLastShareId)) {
  $log->logError("Failed to fetch Last Share ID. ERROR: " . $setting->getCronError());
}

// Fetch all unaccounted blocks
$log->logInfo("\tFetching unaccounted blocks.");
if ($aAllBlocks = $block->getAllUnaccounted('ASC')) {
  // Go through blocks and archive/delete shares that have been accounted for
  foreach ($aAllBlocks as $iIndex => $aBlock) {
    // If we are running through more than one block, check for previous share ID
    $log->logInfo("\tProcess each block for Previous Share ID.");
    $iLastBlockShare = @$aAllBlocks[$iIndex - 1]['share_id'] ? @$aAllBlocks[$iIndex - 1]['share_id'] : 0;
    if (!is_numeric($aBlock['share_id'])) {
      $log->logFatal("Block " . $aBlock['height'] . " has no share_id associated with it, not going to continue");
      $monitoring->setStatus($cron_name . "_active", "yesno", 0);
      $monitoring->setStatus($cron_name . "_message", "message", "Block " . $aBlock['height'] . " has no share_id associated with it");
      $monitoring->setStatus($cron_name . "_status", "okerror", 1);
      exit(1);
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
