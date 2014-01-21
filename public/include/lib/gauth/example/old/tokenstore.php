<?php

// ok, so this will be our overloading class
require_once("../../lib/ga4php.php");

class myGoogleAuth extends GoogleAuthenticator {
	
	function getData($username) {
		global $dbobject;
		
		$res = $dbobject->query("select users_tokendata from users where users_username='$username'");
		foreach($res as $row) {
			$data = $row["users_tokendata"];
		}
		
		error_log("data was: $data");
		
		return $data;
	}
	
	function putData($username, $data) {
		global $dbobject;
		
		$res = $dbobject->query("update users set users_tokendata='$data' where users_username='$username'");

		return $res;
	}
	
	function getUsers() {
		global $dbobject;
		
		$res = $dbobject->query("select users_username from users");
		$i=0;
		$ar = array();
		
		foreach($res as $row) {
			$ar[$i] = $row["users_username"];
			$i++;
		}
		
		return $ar;
	}
}

?>
