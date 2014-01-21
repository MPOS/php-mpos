<?php

require_once("../lib/ga4php.php");

// just in case

unlink("/tmp/db.sqlite");
$ga = new GoogleAuthenticator("/tmp/db.sqlite");

// first lets try hotp, should be 393101, 805347 then 428248
$ga->createUser("User1", "9732e257c94c9930818d");

if($ga->authenticateUser("User1", "393101")) {
	echo "Passed: correct\n";
} else {
	echo "Failed: INCORRECT\n";
}

if($ga->authenticateUser("User1", "805347")) {
	echo "Passed: correct\n";
} else {
	echo "Failed: INCORRECT\n";
}

if($ga->authenticateUser("User1", "428248")) {
	echo "Passed: correct\n";
} else {
	echo "Failed: INCORRECT\n";
}

if($ga->authenticateUser("User1", "234523")) {
	echo "Passed: INCORRECT\n";
} else {
	echo "Failed: correct\n";
}

if($ga->authenticateUser("User1", "598723")) {
	echo "Passed: correct\n";
} else {
	echo "Failed: INCORRECT\n";
}

?>
