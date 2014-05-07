#!/usr/bin/php
<?php
/* script to force payout */

// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');

$options = getopt("abph");
isset($options['a']) ? $allUsers = true : $allUsers = false;
isset($options['b']) ? $balancedUsers = true : $balancedUsers = false;
isset($options['p']) ? $payoutUsers = true : $payoutUsers = false;
if (isset($options['h'])) {
	echo "Usage " . basename($argv[0]) . " [-a] [-b] [-p] [-h] :" . PHP_EOL;
	echo "  -h       :  Show this help" . PHP_EOL;
	echo "  -p       :  Payout all Users with valid Coin Addresses and Balance" . PHP_EOL;
	echo "  -b       :  Show only pool users with confirmed balance" . PHP_EOL;
	exit(0);
}

// Load 3rd party logging library for running crons
$log = KLogger::instance( BASEPATH . '../logs/forced_payout', KLogger::INFO );
$log->logInfo("Starting Forced Payout...");

// Check and see if the sendmany RPC method is available
// This does not test if it actually works too!
$sendmanyAvailable = ((strpos($bitcoin->help('sendmany'), 'unknown') === FALSE) ? true : false);
if ($sendmanyAvailable)
  $log->logDebug('  sendmany available in coind help command');

// Fetch all users
$users = $user->getAllAssoc();

if (!$payoutUsers) {
	$mask = "| %6s | %20s | %30s | %20s | %15s |";
	$log->logInfo(sprintf($mask, 'ID', 'Username', 'eMail', 'Last Login', 'Balance'));
} else {
	$mask = "| %20s | %15s | %50s |";
	$log->logInfo(sprintf($mask, 'Username', 'Balance', 'Transaction ID'));
}

$totalSavings = 0;

foreach ($users as $user) {
	$id = $user['id'];
	$username = $user['username'];
	$lastLogin  = $user['last_login'];
	$coinAddress = $user['coin_address'];
	$mailAddress = $user['email'];

	// get balances
	$balances = $transaction->getBalance($id);
	$confirmedBalance = $balances['confirmed'];
	$totalSavings += $confirmedBalance;

	if(($balancedUsers || $payoutUsers) && $confirmedBalance == 0)
	  continue;

	if ($payoutUsers) {
		if ($bitcoin->validateaddress($coinAddress)) {
			$transaction_id = 0;
				
			$log->logInfo(sprintf($mask, $username, round($confirmedBalance,8), $transaction_id));
				
		} else {
				
			$log->logFatal('    unable to payout user ' . $username . ', no valid payout address');
			
			/*
			$subject = "Account at " . $setting->getValue('website_name') . "!";
			$body = "Hi ". $username .",\n\nWe have discovered \
			your Payout Address as invalid and you have a Balance of ".  $confirmedBalance . " " . $config['currency'] . " left on our Pool. \n
			Please set a valid Payout Address at " . . " so we can send the Coins you have mined at our Pool \n\nCheers";

			if (mail($mailAddress, $subject, $body)) {
				echo("Email successfully sent!");
			} else {
				echo("Email delivery failed...");
			}
			*/
				
		}
	} else {
		$log->logInfo(sprintf($mask, $id, $username, $mailAddress, strftime("%Y-%m-%d %H:%M:%S", $lastLogin), round($confirmedBalance,8)));
	}
}

?>
