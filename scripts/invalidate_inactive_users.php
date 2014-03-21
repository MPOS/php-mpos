#!/usr/bin/php
<?php
	/* script to validate accounts */

	// Change to working directory
	chdir(dirname(__FILE__));

	// Include all settings and classes
	require_once('shared.inc.php');

	function deleteAccount($username,$id) {
		global $mysqli;


		//DELETE USER ACCOUNT
		$stmt = $mysqli->prepare("DELETE FROM accounts WHERE username LIKE ? and id = ?");
		if ($stmt->bind_param('si', $username, $id) && $stmt->execute() && $result = $stmt->get_result()) {
			print_r ($result->fetch_all(MYSQLI_ASSOC));
		}

		//DELETE USER TRANSACTIONS
		$stmt = $mysqli->prepare("DELETE FROM transactions WHERE account_id = ?");
		if ($stmt->bind_param('i', $id) && $stmt->execute() && $result = $stmt->get_result()) {
			print_r ($result->fetch_all(MYSQLI_ASSOC));
		}

		//DELETE USER NOTIFICATIONS
		$stmt = $mysqli->prepare("DELETE FROM notifications WHERE account_id = ?");
		if ($stmt->bind_param('i', $id) && $stmt->execute() && $result = $stmt->get_result()) {
			print_r ($result->fetch_all(MYSQLI_ASSOC));
		}

		//DELETE USER PAYOUTS
		$stmt = $mysqli->prepare("DELETE FROM payouts WHERE account_id = ?");
		if ($stmt->bind_param('i', $id) && $stmt->execute() && $result = $stmt->get_result()) {
			print_r ($result->fetch_all(MYSQLI_ASSOC));
		}

		//DELETE USER POOL_WORKER
		$stmt = $mysqli->prepare("DELETE FROM pool_worker WHERE account_id = ?");
		if ($stmt->bind_param('i', $id) && $stmt->execute() && $result = $stmt->get_result()) {
			print_r ($result->fetch_all(MYSQLI_ASSOC));
		}

		//DELETE USER STATISTICS_SHARES
		$stmt = $mysqli->prepare("DELETE FROM statistics_shares WHERE account_id = ?");
		if ($stmt->bind_param('i', $id) && $stmt->execute() && $result = $stmt->get_result()) {
		print_r ($result->fetch_all(MYSQLI_ASSOC));
		}

	}

	function notifyUser($notifyAccounts,$timeNoticeInDays3) {
	global $mail;
        foreach ($notifyAccounts as $usr) { 
			$notice = $usr['notice'];
			$username = $usr['username'];
			$email = $usr['email'];
			$id = $usr['id'];
			$delete = $usr['delete'];
			$lastLoginInDays = $usr['lastlogin'];

                   $aData['username'] = $username;
                   $aData['email'] = $email;

		   if ($delete == 1) {
		        echo "Deleted: " . $username . " " . $id . PHP_EOL;
			deleteAccount($username,$id);
		   }
		   if ($notice  == 0) {
             		echo "Sending Termination Notice " . $username . PHP_EOL;
                        $aData['subject'] = 'Account Terminated';
                        $mail->sendMail('notifications/delete', $aData);
		   }
		   elseif ($notice  == 1) {
		   	echo "Sending Notice #1 " . $username . PHP_EOL;
                        $aData['subject'] = "1st WARNING: Account marked for termination ";
                        $mail->sendMail('notifications/inactive', $aData);

		   }
		   elseif ($notice  == 2) {
		   	echo "Sending Notice #2 " . $username . PHP_EOL;
                        $aData['subject'] = "2nd WARNING: Account marked for termination ";
                        $mail->sendMail('notifications/inactive', $aData);

		   }
		   elseif ($notice  == 3) {
		   	echo "Sending Notice #3 " . $username . PHP_EOL;
                        $aData['subject'] = "FINAL WARNING: Account marked for termination in 1 Day";
                        $mail->sendMail('notifications/inactive', $aData);
		   }
	}
	}

	//Allow arguments from cli for crons
	$timeNoticeInDays1 = $argv[1];
	$timeNoticeInDays2 = $argv[2];
	$timeNoticeInDays3 = $argv[3];
	$debug = $argv[4];

	// Fetch all users
	$users = $user->getAllAssoc();

	$mask = "| %6s | %20s | %16s | %20s | %12.12s | %5s | %5s | %12s | %5s | \n";
	printf($mask, 'ID', 'Username', 'LoggedIP', 'Last Login','Days Since', 'Ever', 'Trans', 'Balance','Stale');

	$currentTime = time();
	$totalSavings = 0;
	$notifyAccounts = array();

	foreach ($users as $user) 
	{
		$id = $user['id'];
		$isAdmin = $user['is_admin'];
		$username = $user['username'];
		$email = $user['email'];
		$loggedIp = $user['loggedIp'];
		$lastLogin  = $user['last_login'];
		$coinAddress = $user['coin_address'];
		$everLoggedIn = !empty($lastLogin);
		$timeDelta = $currentTime - $lastLogin;
		$lastLoginInDays = round(abs($timeDelta)/60/60/24, 0);
		if($lastLoginInDays < $timeNoticeInDays1)
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
		if ($staleAccount == true) {
		   $notifyAccounts[] = array(
		   'username' => $username,
		   'email' => $email,
		   'id' => $id,
		   'lastlogin' => $lastLoginInDays,
		   'notice' => "0",
		   'delete' => "1"
		    );
		}
		else if ($lastLoginInDays > $timeNoticeInDays1 && $lastLoginInDays < $timeNoticeInDays2) {
		   $notifyAccounts[] = array(
		   'username' => $username,
		   'email' => $email,
		   'id' => $id,
		   'lastlogin' => $lastLoginInDays,
		   'notice' => "1",
		   'delete' => "0"
		    );
		}
		else if ($lastLoginInDays > $timeNoticeInDays2 && $lastLoginInDays < $timeNoticeInDays3) {

		   $notifyAccounts[] = array(
		   'username' => $username,
		   'email' => $email,
		   'id' => $id,
		   'lastlogin' => $lastLoginInDays,
		   'notice' => "2",
		   'delete' => "0"
		    );
		}
		else if ($lastLoginInDays > $timeNoticeInDays3 && $lastLoginInDays < $timeNoticeInDays3+2) {

		   $notifyAccounts[] = array(
		   'username' => $username,
		   'email' => $email,
		   'lastlogin' => $lastLoginInDays,
		   'id' => $id,
		   'notice' => "3",
		   'delete' => "0"
		    );
		}
		else if ($lastLoginInDays > $timeNoticeInDays3+2) {

		   $notifyAccounts[] = array(
		   'username' => $username,
		   'email' => $email,
		   'id' => $id,
		   'lastlogin' => $lastLoginInDays,
		   'notice' => "999",
		   'delete' => "0"
		    );
		}

	}

	notifyUser($notifyAccounts,$timeNoticeInDays3);

	echo "Total balance of stale accounts: $totalSavings" . PHP_EOL;
?>
