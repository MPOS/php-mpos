<?php

/*
 * This file just shows a simple example of how to extend the GoogleAuthenticator schema
 */
require_once("../../lib/ga4php.php");


/* we now define the three methods we have to define
 * the way the class can get and save data. The class calls
 * these methods frequently
 * 
 * What we need to define is methods for saving the auth data
 * for the user
 * 
 * Lets assume our application already has a user table, so what 
 * we do is add a new column into our table for saving auth data
 * 
 * i.e. "alter table users add tokendata text" would add a column
 * we can use to our "users" tables... Lets also assume that we
 * have a column called "username" which defines the username
 * 
 * Lastly, lets assume we can get a connection to the database
 * by calling a function GetDatabase(); which is a PDO object
 */

class MyGoogleAuth extends GoogleAuthenticator {
	
	function getData($username) {
		
		// get our database connection
		$dbObject = GetDatabase();
		
		// set the sql for retreiving the data
		$sql = "select tokendata from users where username='$username'";
		
		// run the query
		$result = $dbObject->query($sql);
		
		// check the result
		if(!$result) return false;
		
		// now just retreieve all the data (there should only be one, but whatever)
		$tokendata = false;
		foreach($result as $row) {
			$tokendata = $row["tokendata"];
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
		$dbObject = GetDatabase();
		
		// set the sql for updating the data
		// token data is stored as a base64 encoded string, it should
		// not need to be escaped in any way prior to storing in a database
		// but feel free to call your databases "addslashes" (or whatever)
		// function on $data prior to doing the SQL.
		$sql = "update users set tokendata='$data' where username='$username'";
		
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
	
	// the get users method isnt actually used within the google authenticator
	// class as yet, but it probably will in the future, so feel free to implement it
	function getUsers() {
		// get our database connection
		$dbObject = GetDatabase();
		
		// now the sql again
		$sql = "select username from users";
		
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

// and thats it...
?>
