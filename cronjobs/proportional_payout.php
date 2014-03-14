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
if ($config['payout_system'] != 'prop') {
  $log->logInfo("Please activate this cron in configuration via payout_system = prop");
  exit(0);
}

// Fetch all unaccounted blocks
$aAllBlocks = $block->getAllUnaccounted('ASC');
if (empty($aAllBlocks)) {
  $log->logDebug('No new unaccounted blocks found in database');
  $monitoring->endCronjob($cron_name, 'E0011', 0, true, false);
}

$count = 0;
// Table header for account shares
$strLogMask = "| %10.10s | %-5.5s | %15.15s | %15.15s | %12.12s | %12.12s | %15.15s | %15.15s | %15.15s | %15.15s |";
$log->logInfo(sprintf($strLogMask, 'Block', 'ID', 'Username', 'Valid', 'Invalid', 'Percentage', 'Payout', 'Donation', 'Fee', 'Bonus'));
foreach ($aAllBlocks as $iIndex => $aBlock) {
  // If we have unaccounted blocks without share_ids, they might not have been inserted yet
  if (!$aBlock['share_id']) {
    $log->logError('E0062: Block has no share_id, not running payouts');
    $monitoring->endCronjob($cron_name, 'E0062', 0, true);
  }

  // Fetch last paid block information
  if ($iLastBlockId = $setting->getValue('last_accounted_block_id')) {
    $aLastAccountedBlock = $block->getBlockById($iLastBlockId);
  } else {
    // A fake block to ensure payouts get started on first round
    $iLastBlockId = 0;
    $aLastAccountedBlock = array('height' => 0, 'confirmations' => 1);
  }

  // Ensure we are not paying out twice, ignore if the previous paid block is orphaned (-1 confirmations) and payout anyway
  if ((!$aBlock['accounted'] && $aBlock['height'] > $aLastAccountedBlock['height']) || (@$aLastAccountedBlock['confirmations'] == -1)) {
    $iPreviousShareId = @$aAllBlocks[$iIndex - 1]['share_id'] ? $aAllBlocks[$iIndex - 1]['share_id'] : 0;
    $iCurrentUpstreamId = $aBlock['share_id'];
    $aAccountShares = $share->getSharesForAccounts($iPreviousShareId, $aBlock['share_id']);
    $iRoundShares = $share->getRoundShares($iPreviousShareId, $aBlock['share_id']);
    $config['reward_type'] == 'block' ? $dReward = $aBlock['amount'] : $dReward = $config['reward'];

    if (empty($aAccountShares)) {
      $log->logFatal('No shares found for this block, aborted: ' . $aBlock['height']);
      $monitoring->endCronjob($cron_name, 'E0013', 1, true);
    }

    // Loop through all accounts that have found shares for this round
    foreach ($aAccountShares as $key => $aData) {
      // Skip users with only invalids
      if ($aData['valid'] == 0) {
        continue;
      }
      // Skip entries that have no account ID, user deleted?
      if (empty($aData['id'])) {
        $log->logInfo('User ' . $aData['username'] . ' does not have an associated account, skipping');
        continue;
      }

      // Defaults
      $aData['fee' ] = 0;
      $aData['donation'] = 0;
      $aData['pool_bonus'] = 0;
      $aData['percentage'] = round(( 100 / $iRoundShares ) * $aData['valid'], 8);
      $aData['payout'] = round(( $aData['percentage'] / 100 ) * $dReward, 8);

      // Calculate pool fees if they apply
      if ($config['fees'] > 0 && $aData['no_fees'] == 0)
        $aData['fee'] = round($config['fees'] / 100 * $aData['payout'], 8);

      // Calculate pool bonus if it applies, will be paid from liquid assets!
      if ($config['pool_bonus'] > 0) {
        if ($config['pool_bonus_type'] == 'block') {
          $aData['pool_bonus'] = round(( $config['pool_bonus'] / 100 ) * $dReward, 8);
        } else {
          $aData['pool_bonus'] = round(( $config['pool_bonus'] / 100 ) * $aData['payout'], 8);
        }
      }

      // Calculate donation amount, fees not included
      $aData['donation'] = round($user->getDonatePercent($user->getUserId($aData['username'])) / 100 * ( $aData['payout'] - $aData['fee']), 8);

      // Verbose output of this users calculations
      $log->logInfo(
        sprintf($strLogMask, $aBlock['height'], $aData['id'], $aData['username'], $aData['valid'], $aData['invalid'],
                number_format($aData['percentage'], 8), number_format($aData['payout'], 8), number_format($aData['donation'], 8), number_format($aData['fee'], 8), number_format($aData['pool_bonus'], 8))
      );

      // Update user share statistics
      if (!$statistics->updateShareStatistics($aData, $aBlock['id']))
        $log->logFatal('Failed to update share statistics for ' . $aData['username'] . ': ' . $statistics->getCronError());
      // Add new credit transaction
      if (!$transaction->addTransaction($aData['id'], $aData['payout'], 'Credit', $aBlock['id']))
        $log->logFatal('Failed to insert new Credit transaction to database for ' . $aData['username'] . ': ' . $transaction->getCronError());
      // Add new fee debit for this block
      if ($aData['fee'] > 0 && $config['fees'] > 0)
        if (!$transaction->addTransaction($aData['id'], $aData['fee'], 'Fee', $aBlock['id']))
          $log->logFatal('Failed to insert new Fee transaction to database for ' . $aData['username'] . ': ' . $transaction->getCronError());
      // Add new donation debit
      if ($aData['donation'] > 0)
        if (!$transaction->addTransaction($aData['id'], $aData['donation'], 'Donation', $aBlock['id']))
          $log->logFatal('Failed to insert new Donation transaction to database for ' . $aData['username'] . ': ' . $transaction->getCronError());
      // Add new bonus credit
      if ($aData['pool_bonus'] > 0)
        if (!$transaction->addTransaction($aData['id'], $aData['pool_bonus'], 'Bonus', $aBlock['id']))
          $log->logFatal('Failed to insert new Bonus transaction to database for ' . $aData['username'] . ': ' . $transaction->getCronError());
    }

    // Add block as accounted for into settings table
    $setting->setValue('last_accounted_block_id', $aBlock['id']);

    // Move counted shares to archive before this blockhash upstream share
    if (!$share->moveArchive($iCurrentUpstreamId, $aBlock['id'], $iPreviousShareId))
      $log->logError('Failed to copy shares to archive: ' . $share->getCronError());
    // Delete all accounted shares
    if (!$share->deleteAccountedShares($iCurrentUpstreamId, $iPreviousShareId)) {
      $log->logFatal('Failed to delete accounted shares from ' . $iPreviousShareId . ' to ' . $iCurrentUpstreamId . ', aborted! Error: ' . $share->getCronError());
      $monitoring->endCronjob($cron_name, 'E0016', 1, true);
    }
    // Mark this block as accounted for
    if (!$block->setAccounted($aBlock['id'])) {
      $log->logFatal('Failed to mark block as accounted! Aborted! Error: ' . $block->getCronError());
      $monitoring->endCronjob($cron_name, 'E0014', 1, true);
    }
  } else {
    $log->logFatal('Potential double payout detected for block ' . $aBlock['id'] . '. Aborted.');
    $aMailData = array(
      'email' => $setting->getValue('system_error_email'),
      'subject' => 'Payout Failure: Double Payout',
      'Error' => 'Possible double payout detected',
      'BlockID' => $aBlock['id'],
      'Block Height' => $aBlock['height'],
      'Block Share ID' => $aBlock['share_id']
    );
    if (!$mail->sendMail('notifications/error', $aMailData))
      $log->logError('Failed to send notification mail');
    $monitoring->endCronjob($cron_name, 'E0015', 1, true);
  }
}

require_once('cron_end.inc.php');
?>
