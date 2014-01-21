<?php

if(!isset($MSG_QUEUE_KEY_ID_SERVER)) $MSG_QUEUE_KEY_ID_SERVER = "189751072"; // i would use ftok, but its crap
if(!isset($MSG_QUEUE_KEY_ID_CLIENT)) $MSG_QUEUE_KEY_ID_CLIENT = "189751073"; // ftok is not ok!
global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;

define("MSG_AUTH_USER_TOKEN", 1);
define("MSG_ADD_USER_TOKEN", 2);
define("MSG_DELETE_USER", 3);
define("MSG_AUTH_USER_PASSWORD", 4);
define("MSG_SET_USER_PASSWORD", 5);
define("MSG_SET_USER_REALNAME", 6);
define("MSG_SET_USER_TOKEN", 7);
define("MSG_SET_USER_TOKEN_TYPE", 8);
define("MSG_GET_USERS", 9);
define("MSG_GET_OTK_PNG", 10);
define("MSG_GET_OTK_ID", 11);
define("MSG_DELETE_USER_TOKEN", 12);

// BASE_DIR = 
$BASE_DIR = realpath(dirname(__FILE__)."/../../");
global $BASE_DIR;

// messy
require_once(dirname(__FILE__)."/../../lib/ga4php.php");

function generateRandomString()
{
	$str = "";
	$strpos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	
	for($i=0; $i<128; $i++) {
		$str .= $strpos[rand(0, strlen($strpos)-1)];
	}
	
	return $str;
}


function getDatabase() {
	$dbobject = false;
	global $BASE_DIR;
	if(file_exists("$BASE_DIR/authserver/authd/gaasdata.sqlite")) {
		try {
			$dbobject = new PDO("sqlite:$BASE_DIR/authserver/authd/gaasdata.sqlite");
		} catch(PDOException $exep) {
			error_log("execpt on db open");
		}
	} else {
		try {
			$dbobject = new PDO("sqlite:$BASE_DIR/authserver/authd/gaasdata.sqlite");
		} catch(PDOException $exep) {
			error_log("execpt on db open");
		}
		$sql = 'CREATE TABLE "users" ("users_id" INTEGER PRIMARY KEY AUTOINCREMENT,"users_username" TEXT, "users_realname" TEXT, "users_password" TEXT, "users_tokendata" TEXT, "users_otk" TEXT);';
		$dbobject->query($sql);
	}
	
	return $dbobject;
}

function closeDatabase($db) {
	// doesnt do anything yet
}

class gaasGA extends GoogleAuthenticator {
	function getData($username) {
		echo "called into getdata\n";
		
		// get our database connection
		$dbObject = getDatabase();
		
		// set the sql for retreiving the data
		$sql = "select users_tokendata from users where users_username='$username'";
		
		// run the query
		$result = $dbObject->query($sql);
		
		// check the result
		echo "next1\n";
		if(!$result) return false;
		
		// now just retreieve all the data (there should only be one, but whatever)
		echo "next2\n";
		$tokendata = false;
		foreach($result as $row) {
			$tokendata = $row["users_tokendata"];
		}

		echo "next3, $username, $tokendata\n";
		// now we have our data, we just return it. If we got no data
		// we'll just return false by default
		return $tokendata;
		
		// and there you have it, simple eh?
	}
	
	
	function putData($username, $data) {
		// get our database connection
		$dbObject = getDatabase();
		
		// we need to check if the user exists, and if so put the data, if not create the data
		$sql = "select * from users where users_username='$username'";
		$res = $dbObject->query($sql);
		if($res->fetchColumn() > 0) {
			// do update
			error_log("doing userdata update");
			$sql = "update users set users_tokendata='$data' where users_username='$username'";
		} else {
			// do insert
			error_log("doing user data create");
			$sql = "insert into users values (NULL, '$username', '', '', '$data', '')";
		}
		
		if($dbObject->query($sql)) {
			return true;
		} else {
			return false;
		}

	}
	
	function getUsers() {
		// get our database connection
		$dbObject = getDatabase();
		
		// now the sql again
		$sql = "select users_username from users";
		
		// run the query
		$result = $dbObject->query($sql);
		
		// iterate over the results - we expect a simple array containing
		// a list of usernames
		$i = 0;
		$users = array();
		foreach($result as $row) {
			$users[$i] = $row["username"];
			$i++;
		}
		
		// now return the list
		return $users;
	}	
}

?>
