<?php

$includeDirectory = "/sites/mmc/www/includes/";

include($includeDirectory."requiredFunctions.php");

$bitcoinController = new BitcoinClient($rpcType, $rpcUsername, $rpcPassword, $rpcHost);

// Pay users who have exceeded their threshold setting
$resultQ = mysql_query("SELECT userId, balance, IFNULL(paid, 0) as paid, IFNULL(sendAddress,'') as sendAddress FROM accountBalance WHERE threshold >= 0.10 AND balance > threshold");
while ($resultR = mysql_fetch_object($resultQ)) {
	$currentBalance = $resultR->balance;
	$paid = $resultR->paid;
	$paymentAddress = $resultR->sendAddress;
	$userId = $resultR->userId;

	if ($paymentAddress != '')
	{
		$isValidAddress = $bitcoinController->validateaddress($paymentAddress);
		if($isValidAddress){
			// Subtract TX fee & calculate total amount the pool will pay
			$currentBalance = $currentBalance - 0.0005;
			$tot_paid = $resultR->paid + $currentBalance;

			// Send the BTC!
				// debug
				// echo "sending: ". $currentBalance . " to ". $paymentAddress;

			if($bitcoinController->sendtoaddress($paymentAddress, $currentBalance)) {
				// Reduce balance amount to zero, update total paid amount, and make a ledger entry
				mysql_query("UPDATE `accountBalance` SET balance = '0', paid = '".$tot_paid."' WHERE `userId` = '".$userId."'");

                                mysql_query("INSERT INTO ledger (userId, transType, amount, sendAddress) ".
                                            " VALUES ".
                                            "('$userId', 'Debit_ATP', '$currentBalance', '$paymentAddress')");

			}
		}
	}
}
