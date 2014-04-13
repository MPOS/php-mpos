#!/usr/bin/php
<?php
	/* script to validate accounts */

	// Change to working directory
	chdir(dirname(__FILE__));

	// Include all settings and classes
	require_once('shared.inc.php');
	
	$timeLimitInDays = 90;
	$notifyStaleUsers = False;

	// Fetch all users
	$users = $user->getAllAssoc();

	$mask = "| %6s | %20s | %30s | %16s | %20s | %12.12s | %5s | %5s | %12s | %5s | \n";
	printf($mask, 'ID', 'Username', 'eMail', 'LoggedIP', 'Last Login','Days Since', 'Ever', 'Trans', 'Balance','Stale');

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
		$mailAddress = $user['email']; 
		
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
		
		$staleAccount = $everLoggedIn == false && $transactions_exists == false;
		
		if ($notifyStaleUsers) {
			$subject = "Account at " . $setting->getValue('website_name') . "!";
			$body = "Hi ". $username .",\n\nWe have discovered \
			your username as inactive. Your last login is older than 90 days, \
			please reactivate your Account if you want to mine again, \
			else it will be deleted in 30 days.\n\nBalance left: ".  $confirmedBalance . " " . $config['currency'] . "\n\nCheers";
			
			if (mail($mailAddress, $subject, $body)) {
				echo("Email successfully sent!");
			} else {
				echo("Email delivery failed...");
			}
		}

		printf($mask, $id, $username, $mailAddress, 
					$loggedIp, strftime("%Y-%m-%d %H:%M:%S", $lastLogin), $lastLoginInDays, $everLoggedIn ? 'yes' : 'no', 
					$transactions_exists ? 'yes' : 'no', round($confirmedBalance,8),
					$staleAccount ? 'yes' : 'no'	);				
	}

	echo "Total balance of stale accounts: $totalSavings" . PHP_EOL;
?>