#!/usr/bin/php
<?php
	/* script to validate accounts */

	// Change to working directory
	chdir(dirname(__FILE__));

	// Include all settings and classes
	require_once('shared.inc.php');
	
	$timeLimitInDays = 90;

	// Fetch all users
	$users = $user->getAllAssoc();

	$mask = "| %6s | %20s | %16s | %20s | %12.12s | %5s | %5s | %12s | %5s | \n";
	printf($mask, 'ID', 'Username', 'LoggedIP', 'Last Login','Days Since', 'Ever', 'Trans', 'Balance','Stale');

	$currentTime = time();
	$totalSavings = 0;

	foreach ($users as $user) 
	{
		$id = $user['id'];
		$isAdmin = $user['is_admin'];
		$username = $user['username'];
		$loggedIp = $user['loggedIp'];
		$lastLogin  = $user['last_login'];
		$coinAddress = $user['coin_address']; 
		
		$everLoggedIn = !empty($lastLogin);
		$timeDelta = $currentTime - $lastLogin;
		$lastLoginInDays = round(abs($timeDelta)/60/60/24, 0);
		
		if($lastLoginInDays < $timeLimitInDays)
			continue;
		
		// get transactions summary for the user
		$summary = $transaction->getTransactionSummary($id);	
		$transactions_exists = !empty($summary);
		
		// get balances
		$balances = $transaction->getBalance($id);
		$confirmedBalance = $balances['confirmed'];
		$totalSavings += $confirmedBalance;	
		
		$staleAccount  = $everLoggedIn == false && $transactions_exists == false;	
		
		printf($mask, $id, $username, 
					$loggedIp, strftime("%Y-%m-%d %H:%M:%S", $lastLogin), $lastLoginInDays, $everLoggedIn ? 'yes' : 'no', 
					$transactions_exists ? 'yes' : 'no', round($confirmedBalance,8),
					$staleAccount ? 'yes' : 'no'	);				
	}

	echo "Total balance of stale accounts: $totalSavings" . PHP_EOL;
?>