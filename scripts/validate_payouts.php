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

/**
 * Simple script to fetch all user accounts and their coin addresses, then runs
 * them against the RPC to validate. Will allow admins to find users with invalid addresses.
 **/

// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');
// Command line options
$options = getopt("l:r:");
isset($options['l']) ? $limit = (int)$options['l'] : $limit = (int)1000;
isset($options['r']) ? $rpclimit = (int)$options['r'] : $rpclimit = (int)10000;

// Fetch and merge MP and AP payouts
$aAllMPDebitTxs = $transaction->getTransactions(0, array('type' => 'Debit_MP'), $limit);
$aAllAPDebitTxs = $transaction->getTransactions(0, array('type' => 'Debit_AP'), $limit);
$aAllDebitTxs = array_merge($aAllMPDebitTxs, $aAllAPDebitTxs);

// Fetch transactions from RPC
$aListTransactions = $bitcoin->listtransactions('', $rpclimit);

// We don't need to loop through non-send transaction types
foreach ($aListTransactions as $key => $aTransaction) {
  if ($aTransaction['category'] != 'send') {
    unset($aListTransactions[$key]);
  }
}

// Initilize counters
$total=count($aListTransactions);
$found=0;
$notfound=0;

// Output mask and header
$mask = "| %15.15s | %-15.15s | %-34.34s | %20.20s | %10.10s | %-64.64s |" . PHP_EOL;
printf($mask, 'TX-DB-ID', 'Username', 'Address', 'Amount', 'Status', 'TX-RPC-ID');

// Loop through our DB records
foreach ($aAllDebitTxs as $aDebitTx) {
  $bFound = false;
  $txid = 'n/a';
  foreach($aListTransactions as $key => $aTransaction) {
    if (isset($aTransaction['address']) &&
      $aTransaction['address'] == $aDebitTx['coin_address'] &&
      ((string)($aTransaction['amount'] + $aTransaction['fee'])    == (string)($aDebitTx['amount'] * -1) ||     // Check against transaction - Fee total
       (string)($aTransaction['amount'] + $config['txfee_manual']) == (string)($aDebitTx['amount'] * -1) ||     // Check against txfee_manual deducted
       (string)($aTransaction['amount'] + $config['txfee_auto'])   == (string)($aDebitTx['amount'] * -1) ||     // Check against txfee_auto deducted
       (string)$aTransaction['amount']                             == (string)($aDebitTx['amount'] * -1))       // Check against actual value
      ) {
      unset($aListTransactions[$key]);
      $found++;
      $bFound = true;
      $status = 'FOUND';
      $txid = $aTransaction['txid'];
    }
  }
  if (!$bFound) $status = 'MISSING';
  printf($mask, $aDebitTx['id'], $aDebitTx['username'], $aDebitTx['coin_address'], $aDebitTx['amount'], $status, $txid);
}

// Small summary
echo PHP_EOL . 'Summary: ' . PHP_EOL;
echo '  Total Send TX Records:   ' . $total . PHP_EOL;
echo '  Total Debit Records:     ' . count($aAllDebitTxs) . PHP_EOL;
echo '  Total Records Found:     ' . $found . PHP_EOL;
