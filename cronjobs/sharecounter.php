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
    if ($iPrevBlockTime) {
      echo "Found a previous block with timestamp: $iPrevBlockTime\n";
    }
    $aAccountShares = $share->getSharesForAccountsByTimeframe($aBlock['time'], $iPrevBlockTime);
    $strFinder = $share->getFinderByTimeframe($aBlock['time'], $iPrevBlockTime);
    echo "Block Information:\n";
    echo "Height\tTime\t\tFinder\n\n";
    echo $aBlock['height'] . "\t" . $aBlock['time'] . "\t" . $strFinder . "\n";
    echo "\nShares details:\n\n";
    echo "ID\tUsername\tValid\tInvalid\n\n";
    foreach ($aAccountShares as $aData) {
      echo $aData['id'] . "\t" . $aData['username'] . "\t" . $aData['valid'] . "\t" . $aData['invalid'] . "\n";
    }
    echo "\n";
    // TODO: Find all shares for this blocks round and account the users
    // propotional to their shares for this block
  }
  // TODO: We have accounted all shares for a block so mark it accounted
  // and delete all the shares we just accounted for.
}
