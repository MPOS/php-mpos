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
$strLastBlockHash = @$block->getLast()->blockhash;
if (!$strLastBlockHash) { 
  $strLastBlockHash = '';
}

if ( $bitcoin->can_connect() === true ){
  $aTransactions = $bitcoin->query('listsinceblock', $strLastBlockHash);
  $iDifficulty = $bitcoin->query('getdifficulty');
} else {
  echo "Aborted: " . $bitcoin->can_connect() . "\n";
  exit(1);
}

echo "Blockhash\t\tHeight\tAmount\tConfirmations\tDiff\t\tTime\t\t\tStatus\n";

foreach ($aTransactions['transactions'] as $iIndex => $aData) {
  if ( $aData['category'] == 'generate' || $aData['category'] == 'immature' ) {
    $aBlockInfo = $bitcoin->query('getblock', $aData['blockhash']);
    $aData['height'] = $aBlockInfo['height'];
    $aData['difficulty'] = $iDifficulty;
    echo substr($aData['blockhash'], 0, 15) . "...\t" .
         $aData['height'] . "\t" .
         $aData['amount'] . "\t" .
         $aData['confirmations'] . "\t\t" .
         $aData['difficulty'] . "\t" .
         strftime("%Y-%m-%d %H:%M:%S", $aData['time']) . "\t";
    if ( $block->addBlock($aData) ) {
      echo "Added\n";
    } else {
      echo "Failed" . "\n";
    }
  }
}
?>
