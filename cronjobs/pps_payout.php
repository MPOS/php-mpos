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


// Check if we are set as the payout system
if ($config['payout_system'] != 'pps') {
  $log->logInfo("Please activate this cron in configuration via payout_system = pps\n");
  exit(0);
}

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

// Value per share calculation
// We need to use this instead when running VARDIFF
// $pps_value = number_format(round((1/(65536 * $dDifficulty) * $config['reward']), 12) ,12);
// pps base payout target, default 16 = difficulty 1 shares for vardiff
// (1/(65536 * difficulty) * reward) = (reward / (pow(2,32) * difficulty) * pow(2, 16))

// We support some dynamic reward targets but fall back to our fixed value
// Re-calculate after each run due to re-targets in this loop
if ($config['pps']['reward']['type'] == 'blockavg' && $block->getBlockCount() > 0) {
  $pps_reward = round($block->getAvgBlockReward($config['pps']['blockavg']['blockcount']));
  $log->logInfo("PPS reward using block average, amount: " . $pps_reward . "\tdifficulty: " . $dDifficulty);
} else {
  if ($config['pps']['reward']['type'] == 'block') {
     if ($aLastBlock = $block->getLast()) {
        $pps_reward = $aLastBlock['amount'];
        $log->logInfo("PPS reward using last block, amount: " . $pps_reward . "\tdifficulty: " . $dDifficulty);
     } else {
     $pps_reward = $config['pps']['reward']['default'];
     $log->logInfo("PPS reward using default, amount: " . $pps_reward . "\tdifficulty: " . $dDifficulty);
     }
  } else {
     $pps_reward = $config['pps']['reward']['default'];
     $log->logInfo("PPS reward fixed default, amount: " . $pps_reward . "\tdifficulty: " . $dDifficulty);
  }
}

$pps_value = number_format(round($pps_reward / (pow(2,32) * $dDifficulty) * pow(2, $config['pps_target']), 12) ,12);
//$pps_value = number_format(round((1/(65536 * $dDifficulty) * $pps_reward), 12) ,12);

/**
if ($config['reward_type'] != 'block') {
  $pps_value = number_format(round($config['reward'] / (pow(2,32) * $dDifficulty) * pow(2, $config['pps_target']), 12) ,12);
} else {
  // Try to find the last block value and use that for future payouts, revert to fixed reward if none found
  if ($aLastBlock = $block->getLast()) {
    $pps_value = number_format(round($aLastBlock['amount'] / (pow(2,32) * $dDifficulty) * pow(2, $config['pps_target']), 12) ,12);
  } else {
    $pps_value = number_format(round($config['reward'] / (pow(2,32) * $dDifficulty) * pow(2, $config['pps_target']), 12) ,12);
  }
}
**/
/**
if ($config['reward_type'] != 'block') {
  $pps_value = number_format(round((1/(65536 * $dDifficulty) * $config['reward']), 12) ,12);
} else {
  // Try to find the last block value and use that for future payouts, revert to fixed reward if none found
  if ($aLastBlock = $block->getLast()) {
    $pps_value = number_format(round((1/(65536 * $dDifficulty) * $aLastBlock['amount']), 12) ,12);
  } else {
    $pps_value = number_format(round((1/(65536 * $dDifficulty) * $config['reward']), 12) ,12);
  }
}
**/

// Find our last share accounted and last inserted share for PPS calculations
$iPreviousShareId = $setting->getValue('pps_last_share_id');
$iLastShareId = $share->getLastInsertedShareId();

// Check for all new shares, we start one higher as our last accounted share to avoid duplicates
$aAccountShares = $share->getSharesForAccounts($iPreviousShareId + 1, $iLastShareId);

$log->logInfo("ID\tUsername\tInvalid\tValid\t\tPPS Value\t\tPayout\t\tDonation\tFee");

foreach ($aAccountShares as $aData) {
  // Take our valid shares and multiply by per share value
  $aData['payout'] = number_format(round($aData['valid'] * $pps_value, 8), 8);

  // Defaults
  $aData['fee' ] = 0;
  $aData['donation'] = 0;

  // Calculate block fees
  if ($config['fees'] > 0 && $aData['no_fees'] == 0)
    $aData['fee'] = number_format(round($config['fees'] / 100 * $aData['payout'], 8), 8); 
  // Calculate donation amount
  $aData['donation'] = number_format(round($user->getDonatePercent($user->getUserId($aData['username'])) / 100 * ( $aData['payout'] - $aData['fee']), 8), 8); 

  $log->logInfo($aData['id'] . "\t" .
    $aData['username'] . "\t" .
    $aData['invalid'] . "\t" .
    $aData['valid'] . "\t*\t" .
    $pps_value . "\t=\t" .
    $aData['payout'] . "\t" .
    $aData['donation'] . "\t" .
    $aData['fee']);

  // Add new credit transaction
  if (!$transaction->addTransaction($aData['id'], $aData['payout'], 'Credit_PPS'))
    $log->logError('Failed to add Credit_PPS transaction in database');
  // Add new fee debit for this block
  if ($aData['fee'] > 0 && $config['fees'] > 0)
    if (!$transaction->addTransaction($aData['id'], $aData['fee'], 'Fee_PPS'))
      $log->logError('Failed to add Fee_PPS transaction in database');
  // Add new donation debit
  if ($aData['donation'] > 0)
    if (!$transaction->addTransaction($aData['id'], $aData['donation'], 'Donation_PPS'))
      $log->logError('Failed to add Donation_PPS transaction in database');
}

// Store our last inserted ID for the next run
$setting->setValue('pps_last_share_id', $iLastShareId);

// Fetch all unaccounted blocks
$aAllBlocks = $block->getAllUnaccounted('ASC');
if (empty($aAllBlocks)) {
  $log->logDebug("No new unaccounted blocks found");
}

// Go through blocks and archive/delete shares that have been accounted for
foreach ($aAllBlocks as $iIndex => $aBlock) {
  // If we are running through more than one block, check for previous share ID
  $iLastBlockShare = @$aAllBlocks[$iIndex - 1]['share_id'] ? @$aAllBlocks[$iIndex - 1]['share_id'] : 0;
  if (!is_numeric($aBlock['share_id'])) {
    $log->logFatal("Block " . $aBlock['height'] . " has no share_id associated with it, not going to continue");
    $monitoring->setStatus($cron_name . "_active", "yesno", 0);
    $monitoring->setStatus($cron_name . "_message", "message", "Block " . $aBlock['height'] . " has no share_id associated with it");
    $monitoring->setStatus($cron_name . "_status", "okerror", 1);
    exit(1);
  }
  // Per account statistics
  $aAccountShares = $share->getSharesForAccounts(@$iLastBlockShare, $aBlock['share_id']);
  foreach ($aAccountShares as $key => $aData) {
    if (!$statistics->updateShareStatistics($aData, $aBlock['id']))
      $log->logError("Failed to update stats for this block on : " . $aData['username']);
  }
  // Move shares to archive
  if ($aBlock['share_id'] < $iLastShareId) {
    if (!$share->moveArchive($aBlock['share_id'], $aBlock['id'], @$iLastBlockShare))
      $log->logError("Archving failed");
  }
  // Delete shares
  if ($aBlock['share_id'] < $iLastShareId && !$share->deleteAccountedShares($aBlock['share_id'], $iLastBlockShare)) {
    $log->logFatal("Failed to delete accounted shares from " . $aBlock['share_id'] . " to " . $iLastBlockShare . ", aborting!");
    $monitoring->setStatus($cron_name . "_active", "yesno", 0);
    $monitoring->setStatus($cron_name . "_message", "message", "Failed to delete accounted shares from " . $aBlock['share_id'] . " to " . $iLastBlockShare);
    $monitoring->setStatus($cron_name . "_status", "okerror", 1);
    exit(1);
  }
  // Mark this block as accounted for
  if (!$block->setAccounted($aBlock['id'])) {
    $log->logFatal("Failed to mark block as accounted! Aborting!");
    $monitoring->setStatus($cron_name . "_active", "yesno", 0);
    $monitoring->setStatus($cron_name . "_message", "message", "Failed to mark block " . $aBlock['height'] . " as accounted");
    $monitoring->setStatus($cron_name . "_status", "okerror", 1);
    exit(1);
  }
}

require_once('cron_end.inc.php');
?>
