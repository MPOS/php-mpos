#!/usr/bin/php
<?php

// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');

// Check if we are set as the payout system
if ($config['payout_system'] != 'prop') {
  $log->logInfo("Please activate this cron in configuration via payout_system = prop");
  exit(0);
}

$table_names = array('WC' => 'wc');

$original_coin = $coin; // let's say POT

$convertible_transactions = $transaction->getConvertibleQueue();

$account_id = $convertible_transactions['account_id'];
$transaction_id = $convertible_transactions['transaction_id'];
$amount_to_exchange = $convertible_transactions['amount']; // let's say 20000 (in POT)
$coin_to_credit = $convertible_transactions['convertible']; // let's say WC
$end_coin_table_name = $table_names[$coin_to_credit];

$from_coin_exchange_rate = json_decode(file_get_contents("http://chunkypools.com/api/coin/exchange_rates/$original_coin"));
$end_coin_exchange_rate = json_decode(file_get_contents("http://chunkypools.com/api/coin/exchange_rates/$coin_to_credit"));

// check that we have exchange rates, or die
if (!is_array($from_coin_exchange_rate) || !is_array($end_coin_exchange_rate)) {
  $log->logInfo("Error retrieving exchange rates");
  exit(0);
}

// check that we have good exchange rates, or die
if (!($from_coin_exchange_rate['exchange_rate'] > 0) || !($end_coin_exchange_rate > 0)) {
  $log->logInfo("Error retrieving valid exchange rates");
  exit(0);
}

// 0.48 BTC        = 0.00002400    POT/BTC                     * 20000 POT
$amount_in_bitcoin = $from_coin_exchange_rate['exchange_rate'] * $amount_to_exchange;

// 50,526.315789 WC = 0.48 BTC         / 0.00000950   WC/BTC
$amount_in_end_coin = $amount_in_bitcoin / $end_coin_exchange_rate['exchange_rate'];

// add $amount_in_end_coin to WC transaction table as Credit_PPS
$transaction->addTransaction($account_id, $amount_in_end_coin, 'Credit_PPS');

// add Convertible_Transfer of $amount_to_exchange to POT transaction table
$transaction->addTransaction($account_id, $amount_to_exchange, 'Convertible_Transfer');
$transaction->updateSingle($transaction_id, array('name' => 'archived', 'value' => 1, 'type' => 'i'));


