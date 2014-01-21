<?php

include("../authserver/lib/lib.php");

echo "Doing 50000 string generations\n";
for($l = 0; $l < 50000; $l++) {
	if(($l%1000)==0) {
		echo "At $l\n";
	}
	$str = generateRandomString();
	if(strlen($str)!=128) {
		echo "Failure at ".strlen($str)." with $str\n";
		return false;
	}
}

return true;
?>
