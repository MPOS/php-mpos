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
  $monitoring->endCronjob($cron_name, 'E0006', 1, true);
}

// Fetch all unconfirmed blocks
$aAllBlocks = $block->getAllUnconfirmed(max($config['network_confirmations'],$config['confirmations']));

$header = false;
foreach ($aAllBlocks as $iIndex => $aBlock) {
  $strLogMask = "| %10.10s | %10.10s | %-64.64s | %5.5s | %5.5s | %-8.8s";
  $aBlockInfo = $bitcoin->getblock($aBlock['blockhash']);
  // Fetch this blocks transaction details to find orphan blocks
  $aTxDetails = $bitcoin->gettransaction($aBlockInfo['tx'][0]);
  if ($aTxDetails['details'][0]['category'] == 'orphan') {
    // We have an orphaned block, we need to invalidate all transactions for this one
    if ($block->setConfirmations($aBlock['id'], -1)) {
      $status = 'ORPHAN';
    } else {
      $status = 'ERROR';
      $log->logError("    Block became orphaned but unable to update database entries");
    }
    if (!$header) {
      $log->logInfo(sprintf($strLogMask, 'ID', 'Height', 'Blockhash', 'Old', 'New', 'Status'));
      $header = true;
    }
    $log->logInfo(sprintf($strLogMask, $aBlock['id'], $aBlock['height'], $aBlock['blockhash'], $aBlock['confirmations'], $aBlockInfo['confirmations'], $status));
    continue;
  }
  if (isset($aBlockInfo['confirmations'])) {
    $iRPCConfirmations = $aBlockInfo['confirmations'];
  } else if (isset($aTxDetails['confirmations'])) {
    $iRPCConfirmations = $aTxDetails['confirmations'];
  } else {
    $log->logFatal('    RPC does not return any usable block confirmation information');
    $monitoring->endCronjob($cron_name, 'E0082', 1, true);
  }
  if ($iRPCConfirmations == $aBlock['confirmations']) {
    continue;
  } else {
    if (!$block->setConfirmations($aBlock['id'], $iRPCConfirmations)) {
      $log->logError('    Failed to update block confirmations: ' . $block->getCronMessage());
      $status = 'ERROR';
    } else {
      $status = 'UPDATED';
    }
    if (!$header) {
      $log->logInfo(sprintf($strLogMask, 'ID', 'Height', 'Blockhash', 'Old', 'New', 'Status'));
      $header = true;
    }
    $log->logInfo(sprintf($strLogMask, $aBlock['id'], $aBlock['height'], $aBlock['blockhash'], $aBlock['confirmations'], $iRPCConfirmations, $status));
  }
}

require_once('cron_end.inc.php');
?>
