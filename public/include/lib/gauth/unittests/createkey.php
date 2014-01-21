<?php
require_once("../lib/ga4php.php");

$ga = new GoogleAuthenticator("/dev/null");

echo "creating 10000 keys\n";
$oldkey = "";
for($i = 0; $i < 10000; $i++) {
	$key = $ga->createBase32Key();
	if($oldkey == $key) {
		echo "Two identical keys created";
	}
	$old = $key;
}

echo "Last key: $key\n";
?>
