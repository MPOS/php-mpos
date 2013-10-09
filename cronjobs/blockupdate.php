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

if ( $bitcoin->can_connect() !== true ) {
  $log->logFatal("Failed to connect to RPC server\n");
  $monitoring->setStatus($cron_name . "_active", "yesno", 0); 
  $monitoring->setStatus($cron_name . "_message", "message", "Unable to connect to RPC server");
  $monitoring->setStatus($cron_name . "_status", "okerror", 1); 
  exit(1);
}

// Fetch all unconfirmed blocks
$aAllBlocks = $block->getAllUnconfirmed($config['confirmations']);

$log->logInfo("ID\tHeight\tBlockhash\tConfirmations");
foreach ($aAllBlocks as $iIndex => $aBlock) {
  $aBlockInfo = $bitcoin->query('getblock', $aBlock['blockhash']);
  // Fetch this blocks transaction details to find orphan blocks
  $aTxDetails = $bitcoin->query('gettransaction', $aBlockInfo['tx'][0]);
  $log->logInfo($aBlock['id'] . "\t" . $aBlock['height'] .  "\t" . $aBlock['blockhash'] . "\t" . $aBlock['confirmations'] . " -> " . $aBlockInfo['confirmations']);
  if ($aTxDetails['details'][0]['category'] == 'orphan') {
    // We have an orphaned block, we need to invalidate all transactions for this one
    if ($block->setConfirmations($aBlock['id'], -1)) {
      $log->logInfo("    Block marked as orphan");
    } else {
      $log->logError("    Block became orphaned but unable to update database entries");
    }
    continue;
  }
  if ($aBlock['confirmations'] == $aBlockInfo['confirmations']) {
    $log->logDebug('    No update needed');
  } else if (!$block->setConfirmations($aBlock['id'], $aBlockInfo['confirmations'])) {
    $log->logError('    Failed to update block confirmations');
  }
}

require_once('cron_end.inc.php');
?>
