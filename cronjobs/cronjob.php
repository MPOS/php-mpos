<?php

//Set page starter variables//
$includeDirectory = "/sites/mmc/www/includes/";

//Include site functions
include($includeDirectory."requiredFunctions.php");

//Open a bitcoind connection
$bitcoinController = new BitcoinClient($rpcType, $rpcUsername, $rpcPassword, $rpcHost);

//Get current block number & difficulty
$currentBlockNumber = $bitcoinController->getblocknumber();
$difficulty = $bitcoinController->query("getdifficulty");

//Get site percentage
$sitePercent = 0;
$sitePercentQ = mysql_query("SELECT value FROM settings WHERE setting='sitepercent'");
if ($sitePercentR = mysql_fetch_object($sitePercentQ)) $sitePercent = $sitePercentR->value;

//Setup score variables
$c = .00001;
$f=1;
$f = $sitePercent / 100;
$p = 1.0/$difficulty;
$r = log(1.0-$p+$p/$c);
$B = 50;
$los = log(1/(exp($r)-1));

//Query bitcoind for list of transactions
$transactions = $bitcoinController->query('listtransactions', '', '240');
$numAccounts = count($transactions);

for($i = 0; $i < $numAccounts; $i++){
	// Check for 50BTC in each transaction (even when immature so we can start tracking confirms)
	if($transactions[$i]["amount"] >= 50 && ($transactions[$i]["category"] == "immature" || $transactions[$i]["category"] == "immature")) {

		// At this point we may have found a block, Check to see if this accountAddres is already added to `networkBlocks`
		$accountExistsQ = mysql_query("SELECT id FROM networkBlocks WHERE accountAddress = '".$transactions[$i]["txid"]."' ORDER BY blockNumber DESC LIMIT 0,1")or die(mysql_error());
		$accountExists = mysql_num_rows($accountExistsQ);

		// We have a new immature transaction for 50 BTC or more - make an entry in `networkBlocks` so we can start tracking the confirms
		if(!$accountExists){
			$assoc_block = ($currentBlockNumber + 1) - $transactions[$i]["confirmations"];
			$assoc_timestamp = $transactions[$i]["time"];
			$finder = mysql_fetch_object(mysql_query("SELECT DISTINCT id, username FROM shares where upstream_result = 'Y'"));

			// save the winning share and username (if we know it)
			if ($finder) {
				$last_winning_share = $finder->id;
				$username = $finder->username;
				mysql_query("INSERT INTO winning_shares (blockNumber, username) VALUES ('" .$assoc_block. "', '" .$username. "')");
			} else {
				mysql_query("INSERT INTO winning_shares (blockNumber, username) VALUES ('" .$assoc_block. "', 'unknown')");
			}

			// save the block info so we can track confirms
			mysql_query("INSERT INTO `networkBlocks` (`blockNumber`, `timestamp`, `accountAddress`, `confirms`, `difficulty`) ".
				"VALUES ('$assoc_block', '$assoc_timestamp', '" .$transactions[$i]["txid"]. "', '" .$transactions[$i]["confirmations"]. "', '$difficulty')");

			// score and move shares from this block to shares_history
		        $shareInputQ = "";
		        $i=0;
		        $lastId = 0;
		        $lastScore = 0;

			if ($finder) {
			        $getAllShares = mysql_query("SELECT `id`, `rem_host`, `username`, `our_result`, `upstream_result`, `reason`, `solution`, time FROM `shares` WHERE id <='" .$last_winning_share. "' ORDER BY `id` ASC");
			} else {
			        $getAllShares = mysql_query("SELECT `id`, `rem_host`, `username`, `our_result`, `upstream_result`, `reason`, `solution`, time FROM `shares` ORDER BY `id` ASC");
			}

		        while($share = mysql_fetch_array($getAllShares)){
		                if ($i==0)
		                        $shareInputQ = "INSERT INTO `shares_history` (`blockNumber`, `rem_host`, `username`, `our_result`, `upstream_result`, `reason`, `solution`, time, score) VALUES ";
		                $i++;
		                if($i > 1){
		                        $shareInputQ .= ",";
		                }
		                $score = $lastScore + $r;
		                $shareInputQ .="('".$assoc_block."',
		                                                '".$share["rem_host"]."',
		                                                '".$share["username"]."',
		                                                '".$share["our_result"]."',
		                                                '".$share["upstream_result"]."',
		                                                '".$share["reason"]."',
		                                                '".$share["solution"]."',
		                                                '".$share["time"]."',
		                                                ".$score.")";
		                $lastId = $share["id"];
		                $lastScore = $score;
		                if ($i > 5) {
		                        //Add to `shares_history`
		                        $shareHistoryQ = mysql_query($shareInputQ);

		                        //If the add to shares_history was successful, lets clean up `shares` table
		                        if($shareHistoryQ){
		                                //Delete all from shares whoms "id" is less then $lastId (keep everything that didnt get moved.  Its probably from the new round.
		                                mysql_query("DELETE FROM shares WHERE id <= ".$lastId);
		                        }
		                        $i = 0;
		                }
		        }
			// less than five share entries? still do the same as above.
			$shareHistoryQ = mysql_query($shareInputQ);
        		if($shareHistoryQ){
                		//Delete all from shares whoms "id" is less then $lastId to prevent new "hard-earned" shares to be deleted
                		mysql_query("DELETE FROM shares WHERE id <= ".$lastId);
        		}
			// Count number of shares we needed to solve this block

			// get last block number we found
			$last_winning_blockQ = mysql_query("SELECT DISTINCT blockNumber FROM winning_shares ORDER BY blockNumber DESC LIMIT 1,1");
			$last_winning_blockObj = mysql_fetch_object($last_winning_blockQ);
			$last_winning_block = $last_winning_blockObj->blockNumber;

			$block_share_countQ = mysql_query("SELECT sum(su_count) as total FROM (".
							   "SELECT sum(count) as su_count FROM shares_uncounted where blockNumber > " .$last_winning_block. " ".
							   "and blockNumber <= " .$assoc_block. " ".
							    "UNION SELECT count(id) as sh_count from shares_history where blockNumber <= " .$assoc_block. " AND blockNumber > " .$last_winning_block. " AND our_result != 'N' ".
							   ") a");
			$block_share_countObj = mysql_fetch_object($block_share_countQ);

			if($block_share_countObj) {
				mysql_query("UPDATE `winning_shares` SET `shareCount` = " .$block_share_countObj->total. " WHERE blockNumber = " .$assoc_block);
			}
		}
	}
}



///// Update confirms /////

// run thru list of transactions we got from bitcoind and update their confirms (when immature)
for($i = 0; $i < $numAccounts; $i++){
	//if ($transactions[$i]["category"] = "receive")
	if (($transactions[$i]["category"] = "immature") || ($transactions[$i]["category"] = "immature")){
		//Check to see if this account was one of the winning accounts from `networkBlocks`
		$arrayAddress = $transactions[$i]["txid"];
		$winningAccountQ = mysql_query("SELECT id FROM networkBlocks WHERE accountAddress = '".$arrayAddress."' LIMIT 0,1");
		$winningAccount = mysql_num_rows($winningAccountQ);

		if($winningAccount > 0){
			//This is a winning account
			$winningAccountObj = mysql_fetch_object($winningAccountQ);
			$winningId = $winningAccountObj->id;
			$confirms = $transactions[$i]["confirmations"];

			//Update X amount of confirms
			mysql_query("UPDATE networkBlocks SET confirms = '".$confirms."' WHERE id = ".$winningId);
		}
	}
}




///// Check for new network block and score and move shares to shares_history if true ///

// refresh the current block number data
$currentBlockNumber = $bitcoinController->getblocknumber();

// check if we have it in the database (if so we exit because we already did this and we were the block finder)
$inDatabaseQ = mysql_query("SELECT `id` FROM `networkBlocks` WHERE `blockNumber` = '$currentBlockNumber' LIMIT 0,1");
$inDatabase = mysql_num_rows($inDatabaseQ);
$finder = mysql_fetch_object(mysql_query("SELECT DISTINCT id, username FROM shares where upstream_result = 'Y'"));

if(!$inDatabase){
	// make an entry in the DB for this new block
        $currentTime = time();
        mysql_query("INSERT INTO `networkBlocks` (`blockNumber`, `timestamp`, `difficulty`) VALUES ('$currentBlockNumber', '$currentTime', '$difficulty')");

	// score and move shares from this block to shares_history
        $shareInputQ = "";
        $i=0;
        $lastId = 0;
        $lastScore = 0;

        $getAllShares = mysql_query("SELECT `id`, `rem_host`, `username`, `our_result`, `upstream_result`, `reason`, `solution`, time FROM `shares` ORDER BY `id` ASC");

        while($share = mysql_fetch_array($getAllShares)){
                if ($i==0)
                        $shareInputQ = "INSERT INTO `shares_history` (`blockNumber`, `rem_host`, `username`, `our_result`, `upstream_result`, `reason`, `solution`, time, score) VALUES ";
                $i++;
                if($i > 1){
                        $shareInputQ .= ",";
                }
                $score = $lastScore + $r;
                $shareInputQ .="('".$currentBlockNumber."',
	                              '".$share["rem_host"]."',
                                      '".$share["username"]."',
                                      '".$share["our_result"]."',
                                      '".$share["upstream_result"]."',
                                      '".$share["reason"]."',
                                      '".$share["solution"]."',
                                      '".$share["time"]."',
                                       ".$score.")";
                $lastId = $share["id"];
                $lastScore = $score;
                if ($i > 5) {
                        //Add to `shares_history`
                        $shareHistoryQ = mysql_query($shareInputQ);

                        //If the add to shares_history was successful, lets clean up `shares` table
                        if($shareHistoryQ){
                                //Delete all from shares whoms "id" is less then $lastId (keep everything that didnt get moved.  Its probably from the new round.
                                mysql_query("DELETE FROM shares WHERE id <= ".$lastId);
                        }
                        $i = 0;
                }
        }
	// less than five share entries? still do the same as above.
	$shareHistoryQ = mysql_query($shareInputQ);
      		if($shareHistoryQ) {
              		//Delete all from shares whoms "id" is less then $lastId to prevent new "hard-earned" shares to be deleted
              		mysql_query("DELETE FROM shares WHERE id <= ".$lastId);
			//exec("cd /sites/mmc/cronjobs/; /usr/bin/php archive.php");
		}
}




///// Proportional Payout Method /////

// Get uncounted share total
$overallReward = 0;
$blocksQ = mysql_query("SELECT DISTINCT s.blockNumber FROM shares_uncounted s, networkBlocks n WHERE s.blockNumber = n.blocknumber AND s.counted=0 AND n.confirms > 119 ORDER BY s.blockNumber ASC");

while ($blocks = mysql_fetch_object($blocksQ)) {
	$block = $blocks->blockNumber;

	// LastNshares - mark all shares below the $lastNshares threshold counted
	$l_bound = 0;
	$total = 0;
	$lastNshares = 1000000;

	$sql = mysql_query("SELECT blockNumber, count FROM ( ".
				"SELECT blockNumber, count FROM `shares_uncounted` WHERE blockNumber <= " .$block. " ".
				"UNION SELECT blockNumber, count FROM `shares_counted` WHERE blockNumber <= " .$block. " AND blockNumber > ".($block - 1000)." ".
			   ")a ORDER BY blockNumber DESC");

	while ($result = mysql_fetch_object($sql)) {

		// increment $total with each row returned
		$total = $total + $result->count;

		// if $lastNshares criteria is met, and $l_bound is not our whole count, set everything below $l_bound as counted = 1
		if ($total >= $lastNshares) {
			$l_bound = $result->blockNumber;

			if ($l_bound < $block) {
				mysql_query("UPDATE shares_uncounted SET counted = 1 WHERE blockNumber < ".$l_bound);
			}
			break;
		}
	}

	$totalRoundSharesQ = mysql_query("SELECT sum(id) as id FROM ( ".
					  "SELECT sum(count) as id FROM shares_uncounted WHERE blockNumber <= ".$block." AND blockNumber >= ".$l_bound." ".
					  "UNION SELECT sum(count) as id FROM shares_counted WHERE blockNumber <= " .$block. " AND blockNumber >= ".$l_bound."".
					 " )a");

	if ($totalRoundSharesR = mysql_fetch_object($totalRoundSharesQ)) {
		$totalRoundShares = $totalRoundSharesR->id;

		$userListCountQ = mysql_query("SELECT userId, sum(id) as id FROM ( ".
						  "SELECT DISTINCT userId, sum(count) as id FROM shares_uncounted WHERE blockNumber <= ".$block." AND blockNumber >= ".$l_bound." GROUP BY userId ".
						  "UNION DISTINCT SELECT userId, sum(count) as id FROM shares_counted WHERE blockNumber <= " .$block. " AND blockNumber >= ".$l_bound." GROUP BY userId ".
						 " )a GROUP BY userId");

		while ($userListCountR = mysql_fetch_object($userListCountQ)) {
			$userInfoR = mysql_fetch_object(mysql_query("SELECT DISTINCT username, donate_percent FROM webUsers WHERE id = '" .$userListCountR->userId. "'"));

			$username = $userInfoR->username;
			$uncountedShares = $userListCountR->id;
			$shareRatio = $uncountedShares/$totalRoundShares;
			$ownerId = $userListCountR->userId;
			$donatePercent = $userInfoR->donate_percent;

			//Take out site percent unless user is of early adopter account type
                        $account_type = account_type($ownerId);
                        if ($account_type == 0) {
				// is normal account
				$predonateAmount = (1-$f)*(50*$shareRatio);
				$predonateAmount = rtrim(sprintf("%f",$predonateAmount ),"0");
				$totalReward = $predonateAmount - ($predonateAmount * ($sitePercent/100));
			} else {
				// is early adopter round 1 0% lifetime fees
				$predonateAmount = 0.9999*(50*$shareRatio);
				$predonateAmount = rtrim(sprintf("%f",$predonateAmount ),"0");
				$totalReward = $predonateAmount;
			}

			if ($predonateAmount > 0.00000001)	{

				//Take out donation
				$totalReward = $totalReward - ($totalReward * ($donatePercent/100));

				//Round Down to 8 digits
				$totalReward = $totalReward * 100000000;
				$totalReward = floor($totalReward);
				$totalReward = $totalReward/100000000;

				//Get total site reward
				$donateAmount = round(($predonateAmount - $totalReward), 8);

				$overallReward += $totalReward;

				//Update account balance & site ledger
				mysql_query("UPDATE accountBalance SET balance = balance + ".$totalReward." WHERE userId = ".$ownerId);

				mysql_query("INSERT INTO ledger (userId, transType, amount, feeAmount, assocBlock) ".
					    " VALUES ".
					    "('$ownerId', 'Credit', '$totalReward', '$donateAmount', '$block')");
			}
			mysql_query("UPDATE shares_uncounted SET counted = 1 WHERE userId='".$ownerId."' AND blockNumber <= ".$block);
		}
		// update site wallet with our reward from this block
		if (isset($B)) {
		 $poolReward = $B -$overallReward;
		}
		//mysql_query("UPDATE settings SET value = value +".$poolReward." WHERE setting='sitebalance'");
		mv_uncountedToCounted();
	}
}

function mv_uncountedToCounted() {
	// clean counted shares_uncounted and move to shares_counted
	$sql = "SELECT DISTINCT * FROM shares_uncounted WHERE counted=1";

	$sharesQ = mysql_query($sql);
	$i = 0;
	//$maxId = 0;
	$shareInputSql = "";

	while ($sharesR = mysql_fetch_object($sharesQ)) {
		//if ($sharesR->maxId > $maxId)
		//	$maxId = $sharesR->maxId;
		if ($i == 0) {
			$shareInputSql = "INSERT INTO shares_counted (blockNumber, userId, count, invalid, counted, score) VALUES ";
		}
		if ($i > 0) {
			$shareInputSql .= ",";
		}
		$i++;
		$shareInputSql .= "($sharesR->blockNumber,$sharesR->userId,$sharesR->count,$sharesR->invalid,$sharesR->counted,$sharesR->score)";
		if ($i > 20)
		{
			mysql_query($shareInputSql);
			$shareInputSql = "";
			$i = 0;
		}
	}

	// if not empty, Insert
	if (strlen($shareInputSql) > 0)
		mysql_query($shareInputSql);

	//Remove counted shares from shares_uncounted (this should empty it completely or something went wrong.
	mysql_query("DELETE FROM shares_uncounted WHERE counted = '1'");
}
?>
