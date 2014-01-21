<?php 
/*
 * This example is simply an example of how a provisioning page may look
 * which includes such funcationality as createing users, initialising their
 * data, create a token for them, testing the token and resyncing it as needed
 * 
 */

// Require our php libraries
require_once("token.php");
require_once("dbfunctions.php");
require_once("input.php");

// now lets get an instance of our class
$myga = new myGA();
global $myga;

// this part of the page resonds to user input
processInput();
?>

<html>
<h1>Welcome to GA Provisioning!</h1>

<?php 
// in this part of the code we look for "success" or "fail" things
if(isset($_REQUEST["success"])) {
	echo "<br><font color=\"green\">".$_REQUEST["success"]."</font><br>";
}
if(isset($_REQUEST["failure"])) {
	echo "<br><font color=\"red\">".$_REQUEST["failure"]."</font><br>";
}
?>

<hr>
<b>How to use this page</b> - Create a user with the "Users" form. Once a user is created, then in the "Create Token" form,
select the user from the drop down box and then select a token type, then click "provision". In the main user list section
your user should now have a qrcode representing the key for that user. Pull our your mobile phone (with the google
authenticator app from the market) and scan in the code. Next, select the user who's authentication you wish to test from
the drop down list under "test authentication" section, generate a code for that user on your phone and click "Auth".
this should fail/succeed depending on whether you have typed in the right code.
<hr>



<h2>Users</h2>
<table border="1">
<tr><th>Username/Login</th><th>Fullname</th><th>Has Token?</th><th>Key</th><th>Base 32 Key</th><th>Hex Key</th></tr>
<?php
// now we get our list of users - this part of the page just has a list of users
// and the ability to create new ones. This isnt really in the scope of the
// GA4PHP, but for this example, we need to be able to create users, so heres where
// you do it.
$db = getDatabase();
$result = $db->query("select * from users");
foreach($result as $row) {
	if($myga->hasToken($row["users_username"])) {
		$hastoken = "Yes";
		$type = $myga->getTokenType($row["users_username"]);
		if($type == "HOTP") {
			$type = "- Counter Based";
		} else {
			$type = "- Time Based";
		}
		$hexkey = $myga->getKey($row["users_username"]);
		$b32key = $myga->helperhex2b32($hexkey);
		
		$url = urlencode($myga->createURL($row["users_username"]));
		$keyurl = "<img src=\"http://chart.apis.google.com/chart?cht=qr&chl=$url&chs=100x100\">";
		
	}
	else {
		$b32key = "";
		$hexkey = "";
		$type = "";
		$hastoken = "no";
		$keyurl = "";
	}
	
	
	// now we generate the qrcode for the user
	
	echo "<tr><td>".$row["users_username"]."</td><td>".$row["users_fullname"]."</td><td>$hastoken $type</td><td>$keyurl</td><td>$b32key</td><td>$hexkey</td></tr>";
}
closeDatabase($db);
?>
</table>
Create a User:
<form method="post" action="?action=createuser">
Username/login: <input type="text" name="username">
Full Name: <input type="text" name="fullname">
Password: <input type="password" name="password">
<input type="submit" name="Add" value="Add">
</form>


<hr>



<h2>Create Token</h2>
This form allows you to provision a token for the user<br>
<form method="post" action="?action=provision">
User:<select name="user">
<?php
// here we list the users again for a select clause
$db = getDatabase();
$result = $db->query("select * from users");
foreach($result as $row) {
	if($myga->hasToken($row["users_username"])) $hastoken = "- Has a token";
	else $hastoken = "- No token";
	
	$username = $row["users_username"];
	
	echo "<option value=\"$username\">$username $hastoken</option>";
}
closedatabase($db);
?>
</select>
<br>
Token Type
<select name="tokentype">
<option value="HOTP">Counter Based</option>
<option value="TOTP">Time Based</option>
</select>
<input type="submit" name="Provision" value="Provision">
</form>

<hr>
<h2>Test Authentication</h2>
<form method="post" action="?action=auth">
User:<select name="user">
<?php
// here we list the users again for a select clause
$db = getDatabase();
$result = $db->query("select * from users");
foreach($result as $row) {
	if($myga->hasToken($row["users_username"])) $hastoken = "- Has a token";
	else $hastoken = "- No token";
	
	$username = $row["users_username"];
	
	echo "<option value=\"$username\">$username $hastoken</option>";
}
closedatabase($db);
?>
<input type="text" name="tokencode">
<input type="submit" name="Auth" value="Auth">
</select>

<hr>
<pre>
<?php 

print_r($myga->internalGetData("asdf"));
?>
</pre>

</html>