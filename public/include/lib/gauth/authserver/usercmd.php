<?php
/*
 * 
 * 
 * This file is designed as a "script" extension to freeradius (or some such tool) for radius authentication.
 * Also provided is a simple web interface for managing users in freeradius.
 * 
 * The simple web interface should also provide a mechanism for configuring freeradius itself
 * 
 */

require_once("lib/authClient.php");

$myAC = new GAAuthClient();

/*
define("MSG_AUTH_USER_TOKEN", 1);
define("MSG_ADD_USER_TOKEN", 2);
define("MSG_DELETE_USER", 3);
define("MSG_AUTH_USER_PASSWORD", 4);
define("MSG_SET_USER_PASSWORD", 5);
define("MSG_SET_USER_REALNAME", 6);
define("MSG_SET_USER_TOKEN", 7);
define("MSG_SET_USER_TOKEN_TYPE", 8);

 */
if(!isset($argv[1])) {
	echo "Usage: ".$argv[0]." command username [args]\n";
	echo "\tadd: add <username> - returns token code url\n";
	echo "\tauth: auth <username> <passcode> - returns 0/1 for pass/fail\n";
	echo "\tdelete: delete <username> - deletes user\n";
	echo "\tauthpass: authpass <username> <password> - returns 0/1 for pass/fail\n";
	echo "\tsetpass: setpass <username> <password> - sets a password for a user (x to remove pass)\n";
	echo "\tsetname: setname <username> <realname> - sets the real name for a user\n";
	echo "\tsettoken: settoken <username> <tokenkey> - sets the key (hex) for a token\n";
	echo "\tsettype: settype <username> <tokentype> - sets a token type for a user\n";
	echo "\tgetusers: getusers - gets a list of users\n";
	echo "\tgetotk: getotk <username> - gets the OTKID for a key\n";
	echo "\tradauth: radauth <username> <pin> - for radius, only returns a code\n";
	return 0;	
}

switch($argv[1]) {
	case "radauth":
		if($myAC->authUserToken($argv[2], $argv[3])==1) {
			syslog(LOG_WARNING, "Got good request for user, ".$argv[2]);
			exit(0);
		} else {
			syslog(LOG_WARNING, "Got bad request for user, ".$argv[2]);
			exit(255);
		}
		break;
	case "getotk":
		$val = $myAC->getOtkID($argv[2]);
		if($val === false) {
			echo "Failure\n";
		} else {
			echo "$val\n";
		}
		break;
	case "auth":
		if($myAC->authUserToken($argv[2], $argv[3])==1) {
			echo "Pass!\n";
		} else {
			echo "Fail!\n";
		}
		break;
	case "add":
		$return = $myAC->addUser($argv[2]);
		echo "Created user, ".$argv[2]." returned $return\n";
		break;
	case "delete":
		$res = $myAC->deleteUser($argv[2]);
		if($res) {
			echo "Deleted\n";
		} else {
			echo "Failure?\n";
		}
		break;
	case "authpass":
		$ret = $myAC->authUserPass($argv[2], $argv[3]);
		if($ret) echo "Authenticated\n";
		else echo "Failed\n";
		break;
	case "setpass":
		$res = $myAC->setUserPass($argv[2], $argv[3]);
		if($res) echo "Password Set\n";
		else echo "Failure?\n";
		break;
	case "setname":
		$ret = $myAC->setUserRealName($argv[2], $argv[3]);
		if($ret) echo "Real Name Set\n";
		else echo "Failure?\n";
		break;
	case "settoken":
		$ret = $myAC->setUserToken($argv[2], $argv[3]);
		if($ret) echo "Token Set\n";
		else echo "Failure?\n";
		break;
	case "settype":
		$ret = $myAC->setUserTokenType($argv[2], $argv[3]);
		if($ret) echo "Token Type Set\n";
		else echo "Failure?\n";
		break;
	case "getusers":
		$users = $myAC->getUsers();
		foreach($users as $user) {
			if($user["realname"] != "") $realname = $user["realname"];
			else $realname = "- Not Set -";
			
			if($user["haspass"]) $haspass = "Yes";
			else $haspass = "No";
			
			if($user["hastoken"]) $hastoken = "Yes";
			else $hastoken = "No";
			
			echo "Username: ".$user["username"]."\n";
			echo "\tReal Name: ".$realname."\n";
			echo "\tHas Password?: ".$haspass."\n";
			echo "\tHas Token?: ".$hastoken."\n\n";
		}
		break;
}
?>