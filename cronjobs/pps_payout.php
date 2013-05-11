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

// Fetch all unaccounted blocks
$aAllBlocks = $block->getAllUnaccounted('ASC');
if (empty($aAllBlocks)) {
  verbose("No new unaccounted blocks found\n");
  exit(0);
}

foreach ($aAllBlocks as $iIndex => $aBlock) {
  if (!$aBlock['accounted']) {
    $iPrevBlockTime = @$aAllBlocks[$iIndex - 1]['time'];
    if (!$iPrevBlockTime) {
      $iPrevBlockTime = 0;
    }
    $aAccountShares = $share->getSharesForAccountsByTimeframe($aBlock['time'], $iPrevBlockTime);
    if (empty($aAccountShares)) {
      verbose("No shares found for this block\n");
      continue;
    }
    $iRoundShares = $share->getRoundSharesByTimeframe($aBlock['time'], $iPrevBlockTime);
    $strFinder = $share->getFinderByTimeframe($aBlock['time'], $iPrevBlockTime);
    verbose("ID\tHeight\tTime\t\tShares\tFinder\n");
    verbose($aBlock['id'] . "\t" . $aBlock['height'] . "\t" . $aBlock['time'] . "\t" . $iRoundShares . "\t" . $strFinder . "\n\n");
    verbose("ID\tUsername\tValid\tInvalid\tPercentage\tPayout\t\tStatus\n");
    foreach ($aAccountShares as $key => $aData) {
      $aData['percentage'] = number_format(round(( 100 / $iRoundShares ) * $aData['valid'], 10),10);
      $aData['payout'] = number_format(round(( $aData['percentage'] / 100 ) * $config['reward'], 10), 10);
      verbose($aData['id'] . "\t" .
           $aData['username'] . "\t" .
           $aData['valid'] . "\t" .
           $aData['invalid'] . "\t" .
           $aData['percentage'] . "\t" .
           $aData['payout'] . "\t");

      // Do all database updates for statistics and payouts
      $strStatus = "OK";
      if (!$statistics->updateShareStatistics($aData, $aBlock['id']))
        $strStatus = "Stats Failed";
      if (!$transaction->addCredit($aData['id'], $aData['payout'], $aBlock['id']))
        $strStatus = "Transaction Failed";
      verbose("$strStatus\n");
    }
    verbose("------------------------------------------------------------------------\n\n");

    // Move counted shares to archive for this blockhash
    $share->moveArchiveByTimeframe($aBlock['time'], $iPrevBlockTime, $aBlock['id']);
    $block->setAccounted($aBlock['blockhash']);
  }
}
