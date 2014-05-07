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
if ($config['payout_system'] != 'pplns') {
  $log->logInfo("Please activate this cron in configuration via payout_system = pplns");
  exit(0);
}

// Fetch all unaccounted blocks
$aAllBlocks = $block->getAllUnaccounted('ASC');
if (empty($aAllBlocks)) {
  $log->logDebug("No new unaccounted blocks found");
  $monitoring->endCronjob($cron_name, 'E0011', 0, true, false);
}

$log->logDebug('Starting PPLNS payout process');
$count = 0;
foreach ($aAllBlocks as $iIndex => $aBlock) {
  // If we have unaccounted blocks without share_ids, they might not have been inserted yet
  if (!$aBlock['share_id']) {
    $log->logError('E0062: Block ' . $aBlock['id'] . ' has no share_id, not running payouts');
    $monitoring->endCronjob($cron_name, 'E0062', 0, true);
  }

  // We support some dynamic share targets but fall back to our fixed value
  // Re-calculate after each run due to re-targets in this loop
  if ($config['pplns']['shares']['type'] == 'blockavg' && $block->getBlockCount() > 0) {
    $pplns_target = round($block->getAvgBlockShares($aBlock['height'], $config['pplns']['blockavg']['blockcount']));
  } else if ($config['pplns']['shares']['type'] == 'dynamic' && $block->getBlockCount() > 0) {
    $pplns_target = round($block->getAvgBlockShares($aBlock['height'], $config['pplns']['blockavg']['blockcount']) * (100 - $config['pplns']['dynamic']['percent'])/100 + $aBlock['shares'] * $config['pplns']['dynamic']['percent']/100);
  } else {
    $pplns_target = $config['pplns']['shares']['default'];
  }

  // Fetch our last paid block information
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
    if (!is_numeric($iCurrentUpstreamId)) {
      $log->logFatal("Block " . $aBlock['height'] . " has no share_id associated with it, not going to continue");
      $monitoring->endCronjob($cron_name, 'E0012', 1, true);
    }
    $iRoundShares = $share->getRoundShares($iPreviousShareId, $aBlock['share_id']);
    $iNewRoundShares = 0;
    $config['reward_type'] == 'block' ? $dReward = $aBlock['amount'] : $dReward = $config['reward'];
    $aRoundAccountShares = $share->getSharesForAccounts($iPreviousShareId, $aBlock['share_id']);

    $strLogMask = "| %20.20s | %20.20s | %8.8s | %10.10s | %15.15s |";
    $log->logInfo(sprintf($strLogMask, 'PPLNS Target', 'Actual Shares', 'Height', 'Amount', 'Finder'));
    $log->logInfo(sprintf($strLogMask, $pplns_target, $iRoundShares, $aBlock['height'], $aBlock['amount'],  $user->getUsername($aBlock['account_id'])));

    if ($iRoundShares >= $pplns_target) {
      $log->logDebug("  Matching or exceeding PPLNS target of $pplns_target with $iRoundShares");
      $iMinimumShareId = $share->getMinimumShareId($pplns_target, $aBlock['share_id']);
      // We need to go one ID lower due to `id >` or we won't match if minimum share ID == $aBlock['share_id']
      $aAccountShares = $share->getSharesForAccounts($iMinimumShareId - 1, $aBlock['share_id']);
      if (empty($aAccountShares)) {
        $log->logFatal("  No shares found for this block, aborted! Block Height : " . $aBlock['height'] . ', Block ID: ' . $aBlock['id']);
        $monitoring->endCronjob($cron_name, 'E0013', 1, true);
      }
      foreach($aAccountShares as $key => $aData) {
        $iNewRoundShares += $aData['valid'];
      }
      $log->logInfo('  Adjusting round to PPLNS target of ' . $pplns_target . ' shares used ' . $iNewRoundShares);
      $iRoundShares = $iNewRoundShares;
    } else {
      $log->logDebug("  Not able to match PPLNS target of $pplns_target with $iRoundShares");
      // We need to fill up with archived shares
      // Grab the full current round shares since we didn't match target
      $aAccountShares = $aRoundAccountShares;
      if (empty($aAccountShares)) {
        $log->logFatal("  No shares found for this block, aborted! Block height: " . $aBlock['height'] . ', Block ID:' . $aBlock['id']);
        $monitoring->endCronjob($cron_name, 'E0013', 1, true);
      }

      // Grab only the most recent shares from Archive that fill the missing shares
      $log->logInfo('Fetching ' . ($pplns_target - $iRoundShares) . ' additional shares from archive');
      if (!$aArchiveShares = $share->getArchiveShares($pplns_target - $iRoundShares)) {
        $log->logError('Failed to fetch shares from archive, setting target to round total. Error: ' . $share->getCronError());
        $pplns_target = $iRoundShares;
      } else {
        // Add archived shares to users current shares, if we have any in archive
        if (is_array($aArchiveShares)) {
          $log->logDebug('Found shares in archive to match PPLNS target, calculating per-user shares');
          $strLogMask = "| %5.5s | %-20.20s | %15.15s | %15.15s | %15.15s | %15.15s | %15.15s | %15.15s |";
          $log->logDebug(sprintf($strLogMask, 'ID', 'Username', 'Round Valid', 'Archive Valid', 'Total Valid', 'Round Invalid', 'Archive Invalid', 'Total Invalid'));
          foreach($aAccountShares as $key => $aData) {
            if (array_key_exists(strtolower($aData['username']), $aArchiveShares)) {
              $log->logDebug(sprintf($strLogMask, $aData['id'], $aData['username'],
                $aAccountShares[$key]['valid'], $aArchiveShares[strtolower($aData['username'])]['valid'], ($aAccountShares[$key]['valid'] + $aArchiveShares[strtolower($aData['username'])]['valid']),
                $aAccountShares[$key]['invalid'], $aArchiveShares[strtolower($aData['username'])]['invalid'], ($aAccountShares[$key]['invalid'] + $aArchiveShares[strtolower($aData['username'])]['invalid']))
              );
              $aAccountShares[$key]['valid'] += $aArchiveShares[strtolower($aData['username'])]['valid'];
              $aAccountShares[$key]['invalid'] += $aArchiveShares[strtolower($aData['username'])]['invalid'];
            }
          }
          // reverse payout
          if ($config['pplns']['reverse_payout']) {
            $log->logDebug('Reverse payout enabled, adding shelved shares for all users');
            $aSharesData = NULL;
            foreach($aAccountShares as $key => $aData) {
              $aSharesData[strtolower($aData['username'])] = $aData;
            }
            // Add users from archive not in current round
            $strLogMask = "| %-20.20s | %15.15s | %15.15s |";
            $log->logDebug(sprintf($strLogMask, 'Username', 'Shelved Valid', 'Shelved Invalid'));
            foreach($aArchiveShares as $key => $aArchData) {
              if (!array_key_exists(strtolower($aArchData['account']), $aSharesData)) {
                $log->logDebug(sprintf($strLogMask, $aArchData['account'], $aArchData['valid'], $aArchData['invalid']));
                $aArchData['username'] = $aArchData['account'];
                $aSharesData[$aArchData['account']] = $aArchData;
              }
            }
            $aAccountShares = $aSharesData;
          }
        }
        // We tried to fill up to PPLNS target, now we need to check the actual shares to properly payout users
        foreach($aAccountShares as $key => $aData) {
          $iNewRoundShares += $aData['valid'];
        }
      }
    }

    // We filled from archive but still are not able to match PPLNS target, re-adjust
    if ($iRoundShares < $iNewRoundShares) {
      $log->logInfo('Adjusting round target to ' . $iNewRoundShares);
      $iRoundShares = $iNewRoundShares;
    }

    // Merge round shares and pplns shares arrays
    $aTotalAccountShares = NULL;
    foreach($aAccountShares as $key => $aData) {
      $aData['pplns_valid'] = $aData['valid'];
      $aData['pplns_invalid'] = $aData['invalid'];
      $aData['valid'] = 0;
      $aData['invalid'] = 0;
      $aTotalAccountShares[$aData['username']] = $aData;
    }
    foreach($aRoundAccountShares as $key => $aTempData) {
      if (array_key_exists($aTempData['username'], $aTotalAccountShares)) {
        $aTotalAccountShares[$aTempData['username']]['valid'] = $aTempData['valid'];
        $aTotalAccountShares[$aTempData['username']]['invalid'] = $aTempData['invalid'];
      } else {
        $aTempData['pplns_valid'] = 0;
        $aTempData['pplns_invalid'] = 0;
        $aTotalAccountShares[$aTempData['username']] = $aTempData;
      }
    }

    // Table header for account shares
    $strLogMask = "| %5.5s | %-15.15s | %15.15s | %15.15s | %12.12s | %15.15s | %15.15s | %15.15s | %15.15s |";
    $log->logInfo(sprintf($strLogMask, 'ID', 'Username', 'Valid', 'Invalid', 'Percentage', 'Payout', 'Donation', 'Fee', 'Bonus'));

    // Loop through all accounts that have found shares for this round
    foreach ($aTotalAccountShares as $key => $aData) {
      // Skip entries that have no account ID, user deleted?
      if (empty($aData['id'])) {
        $log->logInfo('User ' . $aData['username'] . ' does not have an associated account, skipping');
        continue;
      }
      if ($aData['pplns_valid'] == 0) {
        continue;
      }

      // Payout based on PPLNS target shares, proportional payout for all users
      $aData['percentage'] = round(( 100 / $iRoundShares) * $aData['pplns_valid'], 8);
      $aData['payout'] = round(( $aData['percentage'] / 100 ) * $dReward, 8);
      // Defaults
      $aData['fee' ] = 0;
      $aData['donation'] = 0;
      $aData['pool_bonus'] = 0;

      // Calculate pool fees
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
        sprintf($strLogMask, $aData['id'], $aData['username'], $aData['pplns_valid'], $aData['pplns_invalid'],
                number_format($aData['percentage'], 8), number_format($aData['payout'], 8), number_format($aData['donation'], 8), number_format($aData['fee'], 8), number_format($aData['pool_bonus'], 8)
        )
      );

      // Add new credit transaction
      if (!$transaction->addTransaction($aData['id'], $aData['payout'], 'Credit', $aBlock['id']))
        $log->logFatal('Failed to insert new Credit transaction to database for ' . $aData['username'] . ': ' . $transaction->getCronError() . 'on block ' . $aBlock['id']);
      // Add new fee debit for this block
      if ($aData['fee'] > 0 && $config['fees'] > 0)
        if (!$transaction->addTransaction($aData['id'], $aData['fee'], 'Fee', $aBlock['id']))
          $log->logFatal('Failed to insert new Fee transaction to database for ' . $aData['username'] . ': ' . $transaction->getCronError() . 'on block ' . $aBlock['id']);
      // Add new donation debit
      if ($aData['donation'] > 0)
        if (!$transaction->addTransaction($aData['id'], $aData['donation'], 'Donation', $aBlock['id']))
          $log->logFatal('Failed to insert new Donation transaction to database for ' . $aData['username'] . ': ' . $transaction->getCronError() . 'on block ' . $aBlock['id']);
      // Add new bonus credit
      if ($aData['pool_bonus'] > 0)
        if (!$transaction->addTransaction($aData['id'], $aData['pool_bonus'], 'Bonus', $aBlock['id']))
          $log->logFatal('Failed to insert new Bonus transaction to database for ' . $aData['username'] . ': ' . $transaction->getCronError());
    }

    // Add full round share statistics
    foreach ($aTotalAccountShares as $key => $aRoundData) {
      if (empty($aRoundData['id'])) {
        continue;
      }
      if (!$statistics->insertPPLNSStatistics($aRoundData, $aBlock['id']))
        $log->logError('Failed to insert share statistics for ' . $aRoundData['username'] . ': ' . $statistics->getCronError() . 'on block ' . $aBlock['id']);
    }

    // Store this blocks height as last accounted for
    $setting->setValue('last_accounted_block_id', $aBlock['id']);

    // Move counted shares to archive before this blockhash upstream share
    if (!$share->moveArchive($iCurrentUpstreamId, $aBlock['id'], $iPreviousShareId))
      $log->logError('Failed to copy shares to archive table: ' . $iPreviousShareId . ' to ' . $iCurrentUpstreamId . ' aborting! Error: ' . $share->getCronError());
    // Delete all accounted shares
    if (!$share->deleteAccountedShares($iCurrentUpstreamId, $iPreviousShareId)) {
      $log->logFatal("Failed to delete accounted shares from " . $iPreviousShareId . " to " . $iCurrentUpstreamId . " aborting! Error: " . $share->getCronError());
      $monitoring->endCronjob($cron_name, 'E0016', 1, true);
    }
    // Mark this block as accounted for
    if (!$block->setAccounted($aBlock['id'])) {
      $log->logFatal("Failed to mark block" . $aBlock['id'] . " as accounted! Aborting! Error: " . $block->getCronError());
      $monitoring->endCronjob($cron_name, 'E0014', 1, true);
    }
  } else {
    $log->logFatal('Potential double payout detected for block ' . $aBlock['id'] . '. Aborted.');
    $aMailData = array(
      'email' => $setting->getValue('system_error_email'),
      'subject' => 'Payout processing aborted',
      'Error' => 'Potential double payout detected. All payouts halted until fixed!',
      'BlockID' => $aBlock['id'],
      'Block Height' => $aBlock['height'],
      'Block Share ID' => $aBlock['share_id']
    );
    if (!$mail->sendMail('notifications/error', $aMailData))
      $log->logError("    Failed sending notifications: " . $notification->getCronError());
    $monitoring->endCronjob($cron_name, 'E0015', 1, true);
  }
}

require_once('cron_end.inc.php');
?>
