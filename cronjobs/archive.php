<?php

//Set page starter variables//
$includeDirectory = "/sites/mmc/www/includes/";

//Include site functions
include($includeDirectory."requiredFunctions.php");

// get current block num from bitcoind - $num_blocks_old so we can leave some data in shares_history for hashrates
$bitcoinController = new BitcoinClient($rpcType, $rpcUsername, $rpcPassword, $rpcHost);
$currentBlockNumber = $bitcoinController->getblocknumber();
$num_blocks_old = ($currentBlockNumber - 10);

if (!$num_blocks_old) { die($num_blocks_old); }

// get all shares by user id from shares_history and move to shares_uncounted

	$sql = 	"SELECT DISTINCT p.associatedUserId, blockNumber, sum(s.valid) as valid, IFNULL(sum(si.invalid),0) as invalid, max(maxId) as maxId FROM ".
		"(SELECT DISTINCT username, max(blockNumber) as blockNumber, count(id) as valid, max(id) as maxId FROM shares_history ".
		  "WHERE counted='0' AND our_result='Y' AND blockNumber <= '" .$num_blocks_old. "' GROUP BY username) s ".
		"LEFT JOIN ".
		"(SELECT DISTINCT username, count(id) as invalid FROM shares_history ".
		  "WHERE counted='0' AND our_result='N' AND blockNumber <= '" .$num_blocks_old. "' GROUP BY username) si ".
		"ON s.username=si.username ".
		"INNER JOIN pool_worker p ON p.username = s.username ".
		"GROUP BY associatedUserId";


$sharesQ = mysql_query($sql);
$i = 0;
$maxId = 0;
$shareInputSql = "";

while ($sharesR = mysql_fetch_object($sharesQ)) {
	if ($sharesR->maxId > $maxId)
		$maxId = $sharesR->maxId;
	if ($i == 0) {
		$shareInputSql = "INSERT INTO shares_uncounted (blockNumber, userId, count, invalid, counted, score) VALUES ";
	}
	if ($i > 0) {
		$shareInputSql .= ",";
	}
	$i++;
	$shareInputSql .= "($sharesR->blockNumber,$sharesR->associatedUserId,$sharesR->valid,$sharesR->invalid,0,0)";
	if ($i > 20)
	{
		mysql_query($shareInputSql);
		$shareInputSql = "";
		$i = 0;
	}
}
if (strlen($shareInputSql) > 0)
	mysql_query($shareInputSql);

//Remove counted shares from shares_history
	mysql_query("DELETE FROM shares_history WHERE counted = '0' AND id <= $maxId AND blockNumber <= '" .$num_blocks_old. "'");

?>
