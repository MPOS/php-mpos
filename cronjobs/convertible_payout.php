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

$db_names = array('WC' => 'wc', 'SUM' => 'summer');
$end_coin_exchange_rates = array('WC' => null, 'SUM' => null);
$process = true;

$original_coin = $currency; // let's say POT

$from_coin_exchange_rate = json_decode(file_get_contents("http://chunkypools.com/api/coin/exchange_rates/$original_coin"));
$end_coin_exchange_rate = null;

// check that we have exchange rates, or die
if (!is_object($from_coin_exchange_rate) || !($from_coin_exchange_rate->exchange_rate > 0)) {
  $log->logInfo("Error retrieving $original_coin exchange rates");
  $monitoring->endCronjob($cron_name, 'E0083', 0, true, false);
}

$convertible_transactions = $transaction->getConvertibleQueue();

if (empty($convertible_transactions)) {
  $log->logDebug('No new unaccounted Convertible transactions found in database');
  $monitoring->endCronjob($cron_name, 'E0085', 0, true, false);
}

$total_original = 0;
$total_btc = 0;
$total_end = 0;

foreach ($convertible_transactions as $convertible_transaction) {
  $account_id = $convertible_transaction['account_id'];
  $amount_to_exchange = $convertible_transaction['amount']; // let's say 20000 (in POT)
  $coin_to_credit = $convertible_transaction['convertible']; // let's say WC
  $end_coin_db_name = $db_names[$coin_to_credit];

  if (!isset($end_coin_exchange_rates[$coin_to_credit])) {
    $json_data = file_get_contents("http://chunkypools.com/api/coin/exchange_rates/$coin_to_credit");
    $end_coin_exchange_rate[$coin_to_credit] = json_decode($json_data);
  }

  // check that we have good exchange rates, or die
  if (!is_object($end_coin_exchange_rate[$coin_to_credit]) || !($end_coin_exchange_rate[$coin_to_credit]->exchange_rate > 0)) {
    $log->logInfo("Error retrieving $coin_to_credit exchange rates");
    $monitoring->endCronjob($cron_name, 'E0084', 0, true, false);
  }

  $end_rate = $end_coin_exchange_rate[$coin_to_credit]->exchange_rate;

  $log->logInfo("processing: account $account_id");

  // 0.48 BTC        = 0.00002400    POT/BTC                     * 20000 POT
  $amount_in_bitcoin = $from_coin_exchange_rate->exchange_rate * $amount_to_exchange;
  $total_btc += $amount_in_bitcoin;

  $log->logInfo("amount in btc [$amount_in_bitcoin] = $original_coin rate [$from_coin_exchange_rate->exchange_rate] * amount to exchange [$amount_to_exchange]");

  // 50,526.315789 WC = 0.48 BTC         / 0.00000950   WC/BTC
  $amount_in_end_coin = $amount_in_bitcoin / $end_rate;

  $log->logInfo("amount in end coin [$amount_in_end_coin] = amount in btc [$amount_in_bitcoin] / $coin_to_credit rate [$end_rate]");

  $amount_in_end_coin_minus_fee = $amount_in_end_coin * 0.99;

  $log->logInfo("amount in end coin minus fee [$amount_in_end_coin_minus_fee] = amount in end coin [$amount_in_end_coin] * 0.99");

  if ($process) {
    if (!$transaction->addTransaction($account_id, $amount_in_end_coin_minus_fee, 'Credit_PPS', NULL, NULL, NULL, NULL, $end_coin_db_name)) {
      $log->logFatal("Failed to add Convertible payout as Credit_PPS to $account_id");
      continue;
    }
  }
  $log->logInfo("added $amount_in_end_coin_minus_fee to $end_coin_db_name transaction table as Credit_PPS");
  $total_end += $amount_in_end_coin_minus_fee;

  if ($process) {
    if (!$transaction->addTransaction($account_id, $amount_to_exchange, 'Convertible_Transfer')) {
      $log->logFatal("Failed to add Convertible_Transfer to $account_id for $amount_to_exchange");
    }
  }

  $log->logInfo("added Convertible_Transfer of $amount_to_exchange to $original_coin transaction table");
  $total_original += $amount_to_exchange;

  if ($process) {
    if (!$transaction->setConvertibleArchived($account_id, null)) {
      $log->logFatal("Failed to mark account id $account_id as archived");
     }
  }
  $log->logInfo("marked transaction as archived. done.");
}

$log->logInfo("totals: $original_coin [$total_original], $coin_to_credit [$total_end], BTC [$total_btc]");

require_once('cron_end.inc.php');

