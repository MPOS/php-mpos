<?php

$includeDirectory = "/sites/mmc/www/includes/";

include($includeDirectory."requiredFunctions.php");

////Update share counts

//Update past shares
try {
        $pastSharesQ = mysql_query("SELECT DISTINCT userId, sum(count) AS valid, sum(invalid) AS invalid, id FROM shares_counted GROUP BY userId");
        while ($pastSharesR = mysql_fetch_object($pastSharesQ)) {
                mysql_query("UPDATE webUsers SET share_count = $pastSharesR->valid, stale_share_count = $pastSharesR->invalid WHERE id = $pastSharesR->userId");
        }
} catch (Exception $ex)  {}

///// Update current round shares

// reset counters
mysql_query("UPDATE webUsers SET shares_this_round=0");

try {
        $sql =	"SELECT SUM( id ) AS id, a.associatedUserId ".
		"FROM ( ".
		 "SELECT COUNT( s.id ) AS id, p.associatedUserId ".
		  "FROM shares s, pool_worker p ".
		  "WHERE p.username = s.username ".
		  "AND s.our_result =  'Y' ".
		  "GROUP BY p.associatedUserId ".
		 "UNION SELECT COUNT( s.id ) AS id, p.associatedUserId ".
		  "FROM shares_history s, pool_worker p ".
		  "WHERE p.username = s.username ".
		  "AND s.our_result =  'Y' ".
		  "AND s.counted =  '0' ".
		  "GROUP BY p.associatedUserId ".
		")a ".
		"GROUP BY associatedUserId";

        $result = mysql_query($sql);
        $totalsharesthisround = 0;
        while ($row = mysql_fetch_object($result)) {
                mysql_query("UPDATE webUsers SET shares_this_round = $row->id WHERE id = $row->associatedUserId");
                $totalsharesthisround += $row->id;
        }

        $currentSharesQ = mysql_query("SELECT DISTINCT userId, sum(count) AS valid, sum(invalid) AS invalid, id FROM shares_uncounted GROUP BY userId");
        while ($currentSharesR = mysql_fetch_object($currentSharesQ)) {
                mysql_query("UPDATE webUsers SET shares_this_round = (shares_this_round + $currentSharesR->valid) ".
			    "WHERE id = $currentSharesR->userId");
		$totalsharesthisround += $currentSharesR->valid;
        }

        mysql_query("UPDATE settings SET value = '$totalsharesthisround' WHERE setting='currentroundshares'");
} catch (Exception $ex)  {}

?>
