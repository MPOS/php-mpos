#!/usr/bin/php
<?php
	/* script to validate accounts */

	// Change to working directory
	chdir(dirname(__FILE__));

	// Include all settings and classes
	require_once('shared.inc.php');

  $options = getopt("d:n:ah");
	isset($options['d']) ? $timeLimitInDays = (int)$options['d'] : $timeLimitInDays = 90;
  isset($options['a']) ? $allUsers = true : $allUsers = false;
	isset($options['n']) ? $notifyStaleUsers = true : $notifyStaleUsers = false;
  if (isset($options['h'])) {
    echo "Usage " . basename($argv[0]) . " [-d #] [-a] [-n]:" . PHP_EOL;
    echo "  -h       :  Show this help" . PHP_EOL;
    echo "  -d #     :  Only show users inactive for more that # days" . PHP_EOL;
    echo "  -n       :  Notify stale accounts via e-mail [EXPERIMENTAL]" . PHP_EOL;
    echo "  -a       :  Show all pool users regardless of inactivity" . PHP_EOL;
    exit(0);
  }

	// Fetch all users
	$users = $user->getAllAssoc();

	$mask = "| %6s | %20s | %30s | %16s | %20s | %12.12s | %5s | %5s | %12s | %5s | \n";
	printf($mask, 'ID', 'Username', 'eMail', 'LoggedIP', 'Last Login','Days Since', 'Ever', 'Trans', 'Balance','Stale');

	$currentTime = time();
	$totalSavings = 0;

	foreach ($users as $user) {
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

		if(!$allUsers && $lastLoginInDays < $timeLimitInDays)
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
