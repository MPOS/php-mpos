#!/usr/bin/php
<?php
/*

Copyright:: 2014, Mining Portal Open Source

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
Fetch all database transactions, locate any NULL txids, update and fix on database if any found
 **/

// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');
// Command line options
$options = getopt("hvl:r:t:");
isset($options['l']) ? $limit = (int)$options['l'] : $limit = (int)1000;
if (isset($options['h'])) {
  echo "Usage " . basename($argv[0]) . " [-l #] [-c #]:" . PHP_EOL;
  echo "  -h       :  Show this help" . PHP_EOL;
  echo "  -l #     :  Limit to # last database debit AP/MP transactions, default 1000" . PHP_EOL;
  exit(0);
}

function validateMissing($coinAddress,$amountMissing,$dbid,$username) {
                $json_url = 'http://ltc.blockr.io/api/v1/address/txs/' . $coinAddress;
                $ch = curl_init($json_url);
                $options = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
                );
                curl_setopt_array( $ch, $options );
                $result =  curl_exec($ch);
                $data = json_decode($result);
                foreach ($data->data->txs as $tx) {
                $data = json_decode($result);
                foreach ($data->data->txs as $tx) {
                        if ($amountMissing == $tx->amount) {
                              $status = "FOUND";
                              $txid = $tx->tx;
                              $mask = "| %15.15s | %-15.15s | %-34.34s | %10.10s | %10.10s | %-64.64s |" . PHP_EOL;
                              printf($mask,$dbid,$username,$coinAddress, $amountMissing, $status, $txid);
                              updateMissing($coinAddress,$amountMissing,$dbid,$username,$txid);
                        }
                }
}

function updateMissing($coinAddress,$amountMissing,$dbid,$username,$txid){
        global $mysqli;
         //UPDATE TRANSACTION ID
         $stmt = $mysqli->prepare("UPDATE transactions SET txid = ?, archived = 1 where id = ?");
         if ($stmt->bind_param('si', $txid, $dbid) && $stmt->execute() && $result = $stmt->get_result()) {
             print_r ($result->fetch_all(MYSQLI_ASSOC));
         }
         $status = "UPDATED";
         $mask = "| %15.15s | %-15.15s | %-34.34s | %10.10s | %10.10s | %-64.64s |" . PHP_EOL;
         printf($mask,$dbid,$username,$coinAddress, $amountMissing, $status, $txid);

}

// Fetch and merge MP and AP payouts $aAllMPDebitTxs = $transaction->getTransactions(0, array('type' => 'Debit_MP'), $limit); $aAllAPDebitTxs =
$aAllMPDebitTxs = $transaction->getTransactions(0, array('type' => 'Debit_MP'), $limit);
$aAllAPDebitTxs = $transaction->getTransactions(0, array('type' => 'Debit_AP'), $limit);
$aAllDebitTxs = array_merge($aAllMPDebitTxs, $aAllAPDebitTxs);

//var_dump($aAllDebitTxs);

// Output mask and header
$mask = "| %15.15s | %-15.15s | %-34.34s | %10.10s | %10.10s | %-64.64s |" . PHP_EOL;
printf($mask, 'TX-DB-ID', 'Username', 'Address', 'Amount', 'Status', 'TX-RPC-ID');


// Loop through our DB records
foreach ($aAllDebitTxs as $aDebit) {
        if ($aDebit['txid'] == NULL) {
                $aMissing[] = $aDebit;
        }
}

if (count($aMissing) > 0) {
        foreach ($aMissing as $aDebitTx) {
              $mask = "| %15.15s | %-15.15s | %-34.34s | %10.10s | %10.10s | %-64.64s |" . PHP_EOL;
              $status = "MISSING";
              echo "\n";
              printf($mask,$aDebitTx['id'],$aDebitTx['username'],$aDebitTx['coin_address'], $aDebitTx['amount'], $status, $aDebitTx['txid']);
              validateMissing($aDebitTx['coin_address'], $aDebitTx['amount'], $aDebitTx['id'], $aDebitTx['username']);
              echo "\n";
        }
}
