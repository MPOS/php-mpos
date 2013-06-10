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

// Fetch all transactions since our last block
if ( $bitcoin->can_connect() === true ){
  $dDifficulty = $bitcoin->getdifficulty();
} else {
  verbose("Aborted: " . $bitcoin->can_connect() . "\n");
  exit(1);
}

// Value per share calculation
$pps_value = number_format(round(50 / (pow(2,32) * $dDifficulty) * pow(2, $config['difficulty']), 12) ,12);

// Find our last share accounted and last inserted share for PPS calculations
$iPreviousShareId = $setting->getValue('pps_last_share_id');
$iLastShareId = $share->getLastInsertedShareId();

// Check for all new shares, we start one higher as our last accounted share to avoid duplicates
$aAccountShares = $share->getSharesForAccounts($iPreviousShareId + 1, $iLastShareId);

verbose("ID\tUsername\tInvalid\tValid\t\tPPS Value\t\tPayout\t\tDonation\tFee\t\tStatus\n");

foreach ($aAccountShares as $aData) {
  // Take our valid shares and multiply by per share value
  $aData['payout'] = number_format(round($aData['valid'] * $pps_value, 8), 8);

  // Defaults
  $aData['fee' ] = 0;
  $aData['donation'] = 0;

  // Calculate block fees
  if ($config['fees'] > 0)
    $aData['fee'] = number_format(round($config['fees'] / 100 * $aData['payout'], 8), 8); 
  // Calculate donation amount
  $aData['donation'] = number_format(round($user->getDonatePercent($user->getUserId($aData['username'])) / 100 * ( $aData['payout'] - $aData['fee']), 8), 8); 

  verbose($aData['id'] . "\t" .
    $aData['username'] . "\t" .
    $aData['invalid'] . "\t" .
    $aData['valid'] . "\t*\t" .
    $pps_value . "\t=\t" .
    $aData['payout'] . "\t" .
    $aData['donation'] . "\t" .
    $aData['fee'] . "\t");

  $strStatus = "OK";
  // Add new credit transaction
  if (!$transaction->addTransaction($aData['id'], $aData['payout'], 'Credit_PPS'))
    $strStatus = "Transaction Failed";
  // Add new fee debit for this block
  if ($aData['fee'] > 0 && $config['fees'] > 0)
    if (!$transaction->addTransaction($aData['id'], $aData['fee'], 'Fee_PPS'))
      $strStatus = "Fee Failed";
  // Add new donation debit
  if ($aData['donation'] > 0)
    if (!$transaction->addTransaction($aData['id'], $aData['donation'], 'Donation_PPS'))
      $strStatus = "Donation Failed";
  verbose($strStatus . "\n");
}

// Store our last inserted ID for the next run
$setting->setValue('pps_last_share_id', $iLastShareId);

verbose("\n\n------------------------------------------------------------------------------------\n\n");

// Fetch all unaccounted blocks
$aAllBlocks = $block->getAllUnaccounted('ASC');
if (empty($aAllBlocks)) {
  verbose("No new unaccounted blocks found\n");
}

// Go through blocks and archive/delete shares that have been accounted for
foreach ($aAllBlocks as $iIndex => $aBlock) {
  $dummy = $iIndex - 1;
  if ($config['archive_shares'] && $aBlock['share_id'] < $iLastShareId) {
    $share->moveArchive($aBlock['share_id'], $aBlock['id'], @$aAllBlocks[$dummy]['share_id']);
  }
  if ($aBlock['share_id'] < $iLastShareId && !$share->deleteAccountedShares($aBlock['share_id'], @$aAllBlocks[$dummy]['share_id'])) {
    verbose("\nERROR : Failed to delete accounted shares from " . $aBlock['share_id'] . " to " . @$aAllBlocks[$dummy]['share_id'] . ", aborting!\n");
    exit(1);
  }
}
?>
