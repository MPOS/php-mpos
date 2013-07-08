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
if ($config['payout_system'] != 'prop') {
  $log->logInfo("Please activate this cron in configuration via payout_system = prop");
  exit(0);
}

// Fetch all unaccounted blocks
$aAllBlocks = $block->getAllUnaccounted('ASC');
if (empty($aAllBlocks)) {
  $log->logDebug('No new unaccounted blocks found in database');
  exit(0);
}

$count = 0;
// Table header for account shares
$log->logInfo("ID\tUsername\tValid\tInvalid\tPercentage\tPayout\t\tDonation\tFee");
foreach ($aAllBlocks as $iIndex => $aBlock) {
  if (!$aBlock['accounted']) {
    $iPreviousShareId = @$aAllBlocks[$iIndex - 1]['share_id'] ? $aAllBlocks[$iIndex - 1]['share_id'] : 0;
    $iCurrentUpstreamId = $aBlock['share_id'];
    if (!is_numeric($iCurrentUpstreamId)) {
      $log->logFatal("Block " . $aBlock['height'] . " has no share_id associated with it, not going to continue.");
      $log->logFatal("Please assign a valid share ID to this block to continue the payout process.");
      exit(1);
    }
    $aAccountShares = $share->getSharesForAccounts($iPreviousShareId, $aBlock['share_id']);
    $iRoundShares = $share->getRoundShares($iPreviousShareId, $aBlock['share_id']);
    $config['reward_type'] == 'block' ? $dReward = $aBlock['amount'] : $dReward = $config['reward'];

    if (empty($aAccountShares)) {
      $log->logFatal('No shares found for this block, aborted: ' . $aBlock['height']);
      exit(1);
    }

    // Loop through all accounts that have found shares for this round
    foreach ($aAccountShares as $key => $aData) {
      // Payout based on shares, PPS system
      $aData['percentage'] = number_format(round(( 100 / $iRoundShares ) * $aData['valid'], 8), 8);
      $aData['payout'] = number_format(round(( $aData['percentage'] / 100 ) * $dReward, 8), 8);
      // Defaults
      $aData['fee' ] = 0;
      $aData['donation'] = 0;

      if ($config['fees'] > 0)
        $aData['fee'] = number_format(round($config['fees'] / 100 * $aData['payout'], 8), 8);
      // Calculate donation amount, fees not included
      $aData['donation'] = number_format(round($user->getDonatePercent($user->getUserId($aData['username'])) / 100 * ( $aData['payout'] - $aData['fee']), 8), 8);

      // Verbose output of this users calculations
      $log->logInfo($aData['id'] . "\t" .
           $aData['username'] . "\t" .
           $aData['valid'] . "\t" .
           $aData['invalid'] . "\t" .
           $aData['percentage'] . "\t" .
           $aData['payout'] . "\t" .
           $aData['donation'] . "\t" .
           $aData['fee']);

      // Update user share statistics
      if (!$statistics->updateShareStatistics($aData, $aBlock['id']))
        $log->logFatal('Failed to update share statistics for ' . $aData['username']);
      // Add new credit transaction
      if (!$transaction->addTransaction($aData['id'], $aData['payout'], 'Credit', $aBlock['id']))
        $log->logFatal('Failed to insert new Credit transaction to database for ' . $aData['username']);
      // Add new fee debit for this block
      if ($aData['fee'] > 0 && $config['fees'] > 0)
        if (!$transaction->addTransaction($aData['id'], $aData['fee'], 'Fee', $aBlock['id']))
          $log->logFatal('Failed to insert new Fee transaction to database for ' . $aData['username']);
      // Add new donation debit
      if ($aData['donation'] > 0)
        if (!$transaction->addTransaction($aData['id'], $aData['donation'], 'Donation', $aBlock['id']))
          $log->logFatal('Failed to insert new Donation transaction to database for ' . $aData['username']);
    }

    // Move counted shares to archive before this blockhash upstream share
    if (!$share->moveArchive($iCurrentUpstreamId, $aBlock['id'], $iPreviousShareId))
      $log->logError('Failed to copy shares to archive');
    // Delete all accounted shares
    if (!$share->deleteAccountedShares($iCurrentUpstreamId, $iPreviousShareId)) {
      $log->logFatal('Failed to delete accounted shares from ' . $iPreviousShareId . ' to ' . $iCurrentUpstreamId . ', aborted');
      exit(1);
    }
    // Mark this block as accounted for
    if (!$block->setAccounted($aBlock['id']))
      $log->logFatal('Failed to mark block as accounted! Aborted.');
  }
}
