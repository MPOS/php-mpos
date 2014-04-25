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

if ($coin != 'POT') {
  $log->logInfo("Coin was: $coin. Only testing POT now.");
  exit(0);
}

$table_names = array('WC' => 'wc');
$end_coin_exchange_rates = array('WC' => null);

$original_coin = $coin; // let's say POT

$from_coin_exchange_rate = json_decode(file_get_contents("http://chunkypools.com/api/coin/exchange_rates/$original_coin"));
$end_coin_exchange_rate = null;

// check that we have exchange rates, or die
if (!is_array($from_coin_exchange_rate) || !($from_coin_exchange_rate['exchange_rate'] > 0)) {
  $log->logInfo("Error retrieving $original_coin exchange rates");
  $monitoring->endCronjob($cron_name, 'E0083', 0, true, false);
}

$convertible_transactions = $transaction->getConvertibleQueue();

if (empty($convertible_transactions)) {
  $log->logDebug('No new unaccounted Convertible transactions found in database');
  $monitoring->endCronjob($cron_name, 'E0085', 0, true, false);
}

foreach ($convertible_transactions as $convertible_transaction) {
  $account_id = $convertible_transaction['account_id'];
  $transaction_id = $convertible_transaction['transaction_id'];
  $amount_to_exchange = $convertible_transaction['amount']; // let's say 20000 (in POT)
  $coin_to_credit = $convertible_transaction['convertible']; // let's say WC
  $end_coin_table_name = $table_names[$coin_to_credit];

  if (!isset($end_coin_exchange_rates[$coin_to_credit])) {
    $json_data = file_get_contents("http://chunkypools.com/api/coin/exchange_rates/$coin_to_credit");
    $end_coin_exchange_rate[$coin_to_credit] = json_decode($json_data);
  }

  // check that we have good exchange rates, or die
  if (!is_array($end_coin_exchange_rate[$coin_to_credit]) || !($end_coin_exchange_rate[$coin_to_credit]['exchange_rate'] > 0)) {
    $log->logInfo("Error retrieving $coin_to_credit exchange rates");
    $monitoring->endCronjob($cron_name, 'E0084', 0, true, false);
  }

  // 0.48 BTC        = 0.00002400    POT/BTC                     * 20000 POT
  $amount_in_bitcoin = $from_coin_exchange_rate['exchange_rate'] * $amount_to_exchange;

  // 50,526.315789 WC = 0.48 BTC         / 0.00000950   WC/BTC
  $amount_in_end_coin = $amount_in_bitcoin / $end_coin_exchange_rate['exchange_rate'];

  // add $amount_in_end_coin to WC transaction table as Credit_PPS
  if (!$transaction->addTransaction($account_id, $amount_in_end_coin, 'Credit_PPS')) {
    $log->logFatal("Failed to add Convertible payout as Credit_PPS to $account_id");
    continue;
  }

  // add Convertible_Transfer of $amount_to_exchange to POT transaction table
  if (!$transaction->addTransaction($account_id, $amount_to_exchange, 'Convertible_Transfer')) {
    $log->logFatal("Failed to add Convertible_Transfer to $account_id for $amount_to_exchange");
  }

  // mark transaction as archived
  if (!$transaction->updateSingle($transaction_id, array('name' => 'archived', 'value' => 1, 'type' => 'i')) {
    $log->logFatal("Failed to mark $transaction_id as archived");
  }
}

