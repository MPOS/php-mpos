<?php

require_once("../../lib/ga4php.php");

// define our token class
class myGA extends GoogleAuthenticator {
	function getData($username) {
		
		// get our database connection
		$dbObject = getDatabase();
		
		// set the sql for retreiving the data
		$sql = "select users_tokendata from users where users_username='$username'";
		
		// run the query
		$result = $dbObject->query($sql);
		
		// check the result
		if(!$result) return false;
		
		// now just retreieve all the data (there should only be one, but whatever)
		$tokendata = false;
		foreach($result as $row) {
			$tokendata = $row["users_tokendata"];
		}
		
		// now we have our data, we just return it. If we got no data
		// we'll just return false by default
		return $tokendata;
		
		// and there you have it, simple eh?
	}
	
	
	// now we need a function for putting the data back into our user table.
	// in this example, we wont check anything, we'll just overwrite it.
	function putData($username, $data) {
		// get our database connection
		$dbObject = getDatabase();
		
		// set the sql for updating the data
		// token data is stored as a base64 encoded string, it should
		// not need to be escaped in any way prior to storing in a database
		// but feel free to call your databases "addslashes" (or whatever)
		// function on $data prior to doing the SQL.
		$sql = "update users set users_tokendata='$data' where users_username='$username'";
		
		// now execute the sql and return straight away - you should probably
		// clean up after yourselves, but im going to assume pdo does this
		// for us anyway in this exmaple
		if($dbObject->query($sql)) {
			return true;
		} else {
			return false;
		}
		
		// even simpler!
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
