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
$aAllBlocks = $block->getAll('ASC');

foreach ($aAllBlocks as $iIndex => $aBlock) {
  if (!$aBlock['accounted']) {
    $iPrevBlockTime = $aAllBlocks[$iIndex - 1]['time'];
    if (!$iPrevBlockTime) {
      $iPrevBlockTime = 0;
    }
    $aAccountShares = $share->getSharesForAccountsByTimeframe($aBlock['time'], $iPrevBlockTime);
    $iRoundShares = $share->getRoundSharesByTimeframe($aBlock['time'], $iPrevBlockTime);
    $strFinder = $share->getFinderByTimeframe($aBlock['time'], $iPrevBlockTime);
    echo "Height\tTime\t\tShares\tFinder\n";
    echo $aBlock['height'] . "\t" . $aBlock['time'] . "\t" . $iRoundShares . "\t" . $strFinder . "\n\n";
    echo "ID\tUsername\tValid\tInvalid\tPercentage\tPayout\n";
    foreach ($aAccountShares as $key => $aData) {
      $aData['percentage'] = ( 100 / $iRoundShares ) * $aData['valid'];
      $aData['payout'] = ( $aData['percentage'] / 100 ) * $config['reward'];
      echo $aData['id'] . "\t" .
           $aData['username'] . "\t" .
           $aData['valid'] . "\t" .
           $aData['invalid'] . "\t" .
           $aData['percentage'] . "\t" .
           $aData['payout'] . "\t" . 
           "\n";
    }
    echo "------------------------------------------------------------------------\n\n";
  }
  // TODO: We have accounted all shares for a block so mark it accounted
  // and delete all the shares we just accounted for.
}
