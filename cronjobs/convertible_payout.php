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

$script_settings_file = "/mpos/cronjobs/settings.json";
$script_settings = json_decode(file_get_contents($script_settings_file));

$process = $script_settings->process;
$payout_mode = $script_settings->payout_mode;

if ($process) {
  echo "WE ARE RUNNING IN PAYOUT MODE." . PHP_EOL;
} else if ($payout_mode) {
  echo "WE ARE RUNNING IN SEND TO EXCHANGE MODE." . PHP_EOL;
} else {
  echo "WE ARE RUNNING IN STATS MODE." . PHP_EOL;
}

$db_names = array('WC' => 'wc', 'SUM' => 'summer', 'BNS' => 'bonus', 'UVC' => 'uni', 'HYPER' => 'hyper');
$end_coin_exchange_rates = array('WC' => null, 'SUM' => null, 'BNS' => null, 'UVC' => null, 'HYPER' => null);

//$from_coin_rate = array('TRC' => 0.00007, 'RZR' => '0.001', 'CRYPT' => '0.000616', 'POT' => 0.00001675, 'MUN' => 0.00000049, 'LGC' => 0.00000700, 'KARM' => 0.000000027, 'RDD' => 0.00000005);
//$end_coin_rate = array('WC' => 0.00000102, 'SUM' => 0.000008, 'BNS' => 0.00000003, 'UVC' => 0.00000151, 'HYPER' => 0.00002100);

require_once('prices.inc.php');

echo "retrieved latest prices." . PHP_EOL;

$tax_rate = 0.02;

$original_coin = $currency; // let's say POT

if ($payout_mode)  {
  echo 'loading exchange addresses.' . PHP_EOL;
  require_once('exchange_payout.php'); 
}

if (!isset($from_coin_rate)) {
  $from_coin_exchange_rate = json_decode(file_get_contents("http://chunkypools.com/api/coin/exchange_rates/$original_coin"));

  // check that we have exchange rates, or die
  if (!is_object($from_coin_exchange_rate) || !($from_coin_exchange_rate->exchange_rate > 0)) {
    $log->logInfo("Error retrieving $original_coin exchange rates");
    $monitoring->endCronjob($cron_name, 'E0083', 0, true, false);
  }

  $from_coin_rate = $from_coin_exchange_rate->exchange_rate;
}

$convertible_transactions = $transaction->getConvertibleQueue();

if (empty($convertible_transactions)) {
  $log->logDebug('No new unaccounted Convertible transactions found in database');
  $monitoring->endCronjob($cron_name, 'E0085', 0, true, false);
}

$total_original = array('WC' => 0, 'SUM' => 0, 'BNS' => 0, 'UVC' => 0, 'HYPER' => 0);
$total_btc = array('WC' => 0, 'SUM' => 0, 'BNS' => 0, 'UVC' => 0, 'HYPER' => 0);
$total_end = array('WC' => 0, 'SUM' => 0, 'BNS' => 0, 'UVC' => 0, 'HYPER' => 0);

foreach ($convertible_transactions as $convertible_transaction) {
  $account_id = $convertible_transaction['account_id'];
  $amount_to_exchange = $convertible_transaction['amount']; // let's say 20000 (in POT)
  $coin_to_credit = $convertible_transaction['convertible']; // let's say WC

  echo "paying account id: $account_id $amount_to_exchange $original_coin in $coin_to_credit" . PHP_EOL;
  if (!isset($coin_to_credit) || empty($coin_to_credit)) {
    $log->logDebug("skipping $account_id because \$coin_to_credit is empty");
    continue;
  }

  $end_coin_db_name = $db_names[$coin_to_credit];
  if (!isset($end_coin_rate[$coin_to_credit])) {
    if (!isset($end_coin_exchange_rates[$coin_to_credit])) {
      $json_data = file_get_contents("http://chunkypools.com/api/coin/exchange_rates/$coin_to_credit");
      $end_coin_exchange_rate[$coin_to_credit] = json_decode($json_data);
    }

    // check that we have good exchange rates, or die
    if (!is_object($end_coin_exchange_rate[$coin_to_credit]) || !($end_coin_exchange_rate[$coin_to_credit]->exchange_rate > 0)) {
      $log->logInfo("Error retrieving $coin_to_credit exchange rates");
      $monitoring->endCronjob($cron_name, 'E0084', 0, true, false);
    }

    $end_coin_rate[$coin_to_credit] = $end_coin_exchange_rate[$coin_to_credit]->exchange_rate;
  }

  $end_rate = $end_coin_rate[$coin_to_credit];

  $log->logInfo("processing: account $account_id");

  // 0.48 BTC        = 0.00002400    POT/BTC                     * 20000 POT
  $amount_in_bitcoin = $from_coin_rate[$original_coin] * $amount_to_exchange;
  $total_btc[$coin_to_credit] += $amount_in_bitcoin;

  $log->logInfo("amount in btc [$amount_in_bitcoin] = $original_coin rate [" . $from_coin_rate[$original_coin] . "] * amount to exchange [$amount_to_exchange]");

  // 50,526.315789 WC = 0.48 BTC         / 0.00000950   WC/BTC
  $amount_in_end_coin = $amount_in_bitcoin / $end_rate;

  $log->logInfo("amount in end coin [$amount_in_end_coin] = amount in btc [$amount_in_bitcoin] / $coin_to_credit rate [$end_rate]");

  $amount_in_end_coin_minus_fee = $amount_in_end_coin * (1 - $tax_rate);

  $log->logInfo("amount in end coin minus fee [$amount_in_end_coin_minus_fee] = amount in end coin [$amount_in_end_coin] * (1 - $tax_rate)");

  if ($process) {
    if (!$transaction->addTransaction($account_id, $amount_in_end_coin_minus_fee, 'Credit_PPS', NULL, NULL, NULL, NULL, $end_coin_db_name)) {
      $log->logFatal("Failed to add Convertible payout as Credit_PPS to $account_id");
      continue;
    }
  }
  $log->logInfo("added $amount_in_end_coin_minus_fee to $end_coin_db_name transaction table as Credit_PPS");
  $total_end[$coin_to_credit] += $amount_in_end_coin_minus_fee;

  if ($process) {
    if (!$transaction->addTransaction($account_id, $amount_to_exchange, 'Convertible_Transfer')) {
      $log->logFatal("Failed to add Convertible_Transfer to $account_id for $amount_to_exchange");
    }
  }

  $log->logInfo("added Convertible_Transfer of $amount_to_exchange to $original_coin transaction table");
  $total_original[$coin_to_credit] += $amount_to_exchange;

  if ($process) {
    if (!$transaction->setConvertibleArchived($account_id, null)) {
      $log->logFatal("Failed to mark account id $account_id as archived");
     }
  }
  $log->logInfo("marked transaction as archived. done.");
}

$total_out = 0.0;

foreach ($total_original as $coin => $blah) {
  $total_string = "totals: $original_coin [" .$total_original[$coin] ."], $coin [" . $total_end[$coin] ."], BTC [" . $total_btc[$coin] ."]";

  $total_out += $total_original[$coin];

  $log->logInfo($total_string);
  print $total_string . PHP_EOL;
}

$daemon_balance = $bitcoin->getrealbalance();

echo "total $original_coin: $total_out" . PHP_EOL; 
echo "balance of $original_coin: $daemon_balance" . PHP_EOL;

if ($payout_mode) {
  echo "paying $total_out to " . $payout_addresses[$original_coin] . PHP_EOL; 

  if ($total_out > ($daemon_balance * 0.9)) {
    $total_out = $daemon_balance * 0.9;
    echo "total is higher than 90% of coin balance. only sending $total_out coins." . PHP_EOL;
  }

  try {
    $tx_id = $bitcoin->sendtoaddress($payout_addresses[$original_coin], $total_out);
    echo "sent $total_out $original_coin. tx id: $tx_id" . PHP_EOL;
  } catch (Exception $e) {
    $log->logError('error sending coins to exchange' . $e->getMessage());
    // Remove this line below if RPC calls are failing but transactions are still added to it
    // Don't blame MPOS if you run into issues after commenting this out!
    $monitoring->endCronjob($cron_name, 'E0078', 1, true);
  }
}

require_once('cron_end.inc.php');

