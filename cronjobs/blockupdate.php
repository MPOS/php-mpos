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

if ( $bitcoin->can_connect() !== true ) {
  verbose("Failed to connect to RPC server\n");
  exit(1);
}

// Fetch all unconfirmed blocks
$aAllBlocks = $block->getAllUnconfirmed($config['confirmations']);

verbose("ID\tBlockhash\tConfirmations\t\n");
foreach ($aAllBlocks as $iIndex => $aBlock) {
  $aBlockInfo = $bitcoin->query('getblock', $aBlock['blockhash']);
  verbose($aBlock['id'] . "\t" . $aBlock['blockhash'] . "\t" . $aBlock['confirmations'] . " -> " . $aBlockInfo['confirmations'] . "\t");
  if ($aBlock['confirmations'] == $aBlockInfo['confirmations']) {
    verbose("SKIPPED\n");
  } else if ($block->setConfirmations($aBlock['id'], $aBlockInfo['confirmations'])) {
    verbose("UPDATED\n");
  } else {
    verbose("ERROR\n");
  }
}
