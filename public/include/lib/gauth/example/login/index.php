<?php 
/* 
 * This example rely's on the provisioning example, you must first create accounts in the provisioning 
 * example then use them here. 
 * 
 * This example is solely an example of how a login page might look and/or work
 * 
 * If a user doesnt have a token assigned, they wont require it on the login page. This is an example
 * of when your allowing the user to increase security of their OWN account, not the security of the
 * site as such.
 * 
 */ 

require_once("../provisioning/dbfunctions.php");
require_once("../provisioning/token.php");


session_start();

$myga = new myGA();

// check if we're logged in
if(isset($_SESSION["loginname"])) {
	if($_SESSION["loginname"]!="") {
		
		// handle logout
		if(isset($_REQUEST["logout"])) {
			error_log("session killer");
			unset($_SESSION["loginname"]);
			header("Location: index.php");
			return;
		}
		
		// display the logged in page
		displayLogedInPage();
		return;
	}
}


// here is where we process the login
if(isset($_REQUEST["login"])) {
	$db = getDatabase();
	
	// get the data from the post request
	error_log("begin login");
	$username = $_REQUEST["username"];
	$password = $_REQUEST["password"];
	$tokencode = $_REQUEST["tokencode"];
	
	// pull the password hash from the database
	$sql = "select users_password from users where users_username='$username'";
	error_log("running sql: $sql");
	$res = $db->query($sql);
	
	foreach($res as $row) {
		$passhash = $row["users_password"];
	}
	
	// user entered a tokencode, fail the login and tell the user
	// if they dont have a token code assigned to them
	if($tokencode != "") {
		if(!$myga->hasToken($username)) {
			$msg = urlencode("Attempted to login with a token when username isnt assigned one");
			header("Location: index.php?failure=$msg");
		}
	}
	
	// check the password hash versus the login password
	error_log("checking $passhash against $password (".sha1($password).")");
	if($passhash == sha1($password)) $passright = true;
	else {
		header("Location: index.php?failure=LoginIncorrect");
		return;
	}
	
	// now get myGA to check the token code
	error_log("passed password auth");
	if($myga->hasToken($username)) if(!$myga->authenticateUser($username, $tokencode)) {
		header("Location: index.php?failure=LoginIncorrect");
		return;
	}

	// and we're loged in
	$_SESSION["loginname"] = "$username";
	
	header("Location: index.php");
	return;
}




// and our "your logged in" page
function displayLogedInPage()
{
?>
<html>
<h1>Welcome</h1>
Welcome <?php echo $_SESSION["loginname"]?>, you are logged in.
Click <a href="index.php?logout">here</a> to log out.
</html>
<?php
	echo "<pre>";
	print_r($_REQUEST);
	print_r($_SESSION);
	echo "</pre>";
	
	return; 
}




?>
<html>
<h2>Welcome to Generic Site</h2>
<i><b>Note:</b> if the user you've provisioned has not got a token code, its not required for login</i><br>
Please login:
<?php
if(isset($_REQUEST["failure"])) {
	echo "<hr><font color=\"red\">Login Failure: ".$_REQUEST["failure"]."</font><hr>";	
} 
?>
<form method="post" action="index.php?login">
<table>
<tr><td>Username</td><td><input type="text" name="username"></td></tr>
<tr><td>Password</td><td><input type="password" name="password"></td></tr>
<tr><td>Pin Code</td><td><input type="text" name="tokencode"></td></tr>
<tr><td><input type="submit" name="login" value="Login"></td></tr>
</table>
</form>
<hr>
<pre>
<?php 
	print_r($_REQUEST);
	print_r($_SESSION);
?>
</pre>
</html>