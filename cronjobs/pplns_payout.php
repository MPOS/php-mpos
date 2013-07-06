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
if ($config['payout_system'] != 'pplns') {
  verbose("Please activate this cron in configuration via payout_system = pplns\n");
  exit(0);
}

// Fetch all unaccounted blocks
$aAllBlocks = $block->getAllUnaccounted('ASC');
if (empty($aAllBlocks)) {
  verbose("No new unaccounted blocks found\n");
  exit(0);
}

// We support some dynamic share targets but fall back to our fixed value
if ($config['pplns']['shares']['type'] == 'blockavg' && $block->getBlockCount() > 0) {
  $pplns_target = round($block->getAvgBlockShares($config['pplns']['type']['blockavg']['blockcount']));
} else {
  $pplns_target = $config['pplns']['shares']['default'] ;
}

$count = 0;
foreach ($aAllBlocks as $iIndex => $aBlock) {
  if (!$aBlock['accounted']) {
    $iPreviousShareId = @$aAllBlocks[$iIndex - 1]['share_id'] ? $aAllBlocks[$iIndex - 1]['share_id'] : 0;
    $iCurrentUpstreamId = $aBlock['share_id'];
    if (!is_number($iCurrentUpstreamId)) die("Block " . $aBlock['height'] . " has no share_id associated with it, not going to continue\n");
    $iRoundShares = $share->getRoundShares($iPreviousShareId, $aBlock['share_id']);
    $config['reward_type'] == 'block' ? $dReward = $aBlock['amount'] : $dReward = $config['reward'];
    $aRoundAccountShares = $share->getSharesForAccounts($iPreviousShareId, $aBlock['share_id']);

    if ($iRoundShares >= $pplns_target) {
      verbose("Matching or exceeding PPLNS target of $pplns_target\n");
      $aAccountShares = $share->getSharesForAccounts($aBlock['share_id'] - $pplns_target + 1, $aBlock['share_id']);
    } else {
      verbose("Not able to match PPLNS target of $pplns_target\n");
      // We need to fill up with archived shares
      // Grab the full current round shares since we didn't match target
      $aAccountShares = $aRoundAccountShares;
      // Grab only the most recent shares from Archive that fill the missing shares
      $aArchiveShares = $share->getArchiveShares($share->getMaxArchiveShareId() - ($pplns_target- $iRoundShares) + 1, $share->getMaxArchiveShareId());
      // Add archived shares to users current shares, if we have any in archive
      if (is_array($aArchiveShares)) {
        foreach($aAccountShares as $key => $aData) {
          if (array_key_exists($aData['username'], $aArchiveShares)) {
            $aAccountShares[$key]['valid'] += $aArchiveShares[$aData['username']]['valid'];
            $aAccountShares[$key]['invalid'] += $aArchiveShares[$aData['username']]['invalid'];
          }
        }
      }
    }
    if (empty($aAccountShares)) {
      verbose("\nNo shares found for this block\n\n");
      sleep(2);
      continue;
    }

    // Table header for account shares
    verbose("ID\tUsername\tValid\tInvalid\tPercentage\tPayout\t\tDonation\tFee\t\tStatus\n");

    // Loop through all accounts that have found shares for this round
    foreach ($aAccountShares as $key => $aData) {
      // Payout based on PPLNS target shares, proportional payout for all users
      $aData['percentage'] = number_format(round(( 100 / $pplns_target) * $aData['valid'], 8), 8);
      $aData['payout'] = number_format(round(( $aData['percentage'] / 100 ) * $dReward, 8), 8);
      // Defaults
      $aData['fee' ] = 0;
      $aData['donation'] = 0;

      if ($config['fees'] > 0)
        $aData['fee'] = number_format(round($config['fees'] / 100 * $aData['payout'], 8), 8);
      // Calculate donation amount, fees not included
      $aData['donation'] = number_format(round($user->getDonatePercent($user->getUserId($aData['username'])) / 100 * ( $aData['payout'] - $aData['fee']), 8), 8);

      // Verbose output of this users calculations
      verbose($aData['id'] . "\t" .
           $aData['username'] . "\t" .
           $aData['valid'] . "\t" .
           $aData['invalid'] . "\t" .
           $aData['percentage'] . "\t" .
           $aData['payout'] . "\t" .
           $aData['donation'] . "\t" .
           $aData['fee'] . "\t");

      $strStatus = "OK";
      // Add full round share statistics, not just PPLNS
      foreach ($aRoundAccountShares as $key => $aRoundData) {
        if ($aRoundData['username'] == $aData['username'])
          if (!$statistics->updateShareStatistics($aRoundData, $aBlock['id']))
            $strStatus = "Stats Failed";
      }
      // Add new credit transaction
      if (!$transaction->addTransaction($aData['id'], $aData['payout'], 'Credit', $aBlock['id']))
        $strStatus = "Transaction Failed";
      // Add new fee debit for this block
      if ($aData['fee'] > 0 && $config['fees'] > 0)
        if (!$transaction->addTransaction($aData['id'], $aData['fee'], 'Fee', $aBlock['id']))
          $strStatus = "Fee Failed";
      // Add new donation debit
      if ($aData['donation'] > 0)
        if (!$transaction->addTransaction($aData['id'], $aData['donation'], 'Donation', $aBlock['id']))
          $strStatus = "Donation Failed";
      verbose("\t$strStatus\n");
    }

    // Move counted shares to archive before this blockhash upstream share
    $share->moveArchive($iCurrentUpstreamId, $aBlock['id'], $iPreviousShareId);
    // Delete all accounted shares
    if (!$share->deleteAccountedShares($iCurrentUpstreamId, $iPreviousShareId)) {
      verbose("\nERROR : Failed to delete accounted shares from $iPreviousShareId to $iCurrentUpstreamId, aborting!\n");
      exit(1);
    }
    // Mark this block as accounted for
    if (!$block->setAccounted($aBlock['id'])) {
      verbose("\nERROR : Failed to mark block as accounted! Aborting!\n");
    }

    verbose("------------------------------------------------------------------------\n\n");
  }
}
