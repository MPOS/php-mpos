<?php

$includeDirectory = "/sites/mmc/www/includes/";
include($includeDirectory."requiredFunctions.php");

//Update workers
	$bitcoinController = new BitcoinClient($rpcType, $rpcUsername, $rpcPassword, $rpcHost);

//Get difficulty
	$difficulty = $bitcoinController->query("getdifficulty");
	//$difficulty = '1';

//Get site percentage fee
$sitePercent = 0;
$sitePercentQ = mysql_query("SELECT value FROM settings WHERE setting='sitepercent'");
if ($sitePercentR = mysql_fetch_object($sitePercentQ)) $sitePercent = $sitePercentR->value;

// set up some scoring variables
$c = .00000001;
$f = $sitePercent / 100;
$p = 1.0/$difficulty;
$r = log(1.0-$p+$p/$c);
$B = 50;
$los = log(1/(exp($r)-1));

// Check for if worker is active (submitted shares in the last 10 mins)
$currentWorkers = 0;
try {
	$sql ="SELECT sum(a.id) IS NOT NULL AS active, p.username FROM pool_worker p LEFT JOIN ".
		  "(SELECT count(id) AS id, username FROM shares WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE) group by username ".
		  "UNION ".
		  "SELECT count(id) AS id, username FROM shares_history WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE) group by username) a ON p.username=a.username group by username";
	$result = mysql_query($sql);
	while ($resultObj = mysql_fetch_object($result)) {
		if ($resultObj->active == 1)
			$currentWorkers += 1;
		mysql_query("UPDATE pool_worker p SET active=".$resultObj->active." WHERE username='".$resultObj->username."'");
	}

	// Update number of workers in our pool status
	$settings->setsetting('currentworkers', $currentWorkers);

} catch (Exception $e) {}


	// Calculate estimated round earnings for each user

        //Proportional estimate
        $totalRoundShares = $settings->getsetting("currentroundshares");

        //if ($totalRoundShares < $difficulty) $totalRoundShares = $difficulty;
        mysql_query("UPDATE webUsers SET round_estimate = round((1-".$f.")*50*(shares_this_round/".$totalRoundShares.")*(1-(donate_percent/100)), 8)");

	// comment the one line below out if you want to disable 0% fees for first 35 users
        mysql_query("UPDATE webUsers SET round_estimate = round(0.9999*50*(shares_this_round/".$totalRoundShares.")*(1-(donate_percent/100)), 8) WHERE account_type = '9'");

