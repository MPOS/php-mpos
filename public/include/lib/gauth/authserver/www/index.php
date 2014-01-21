<?php 

require_once("user_actions.php");

// first check for a token retreival
if(isset($_REQUEST["gettoken"])) {
	$username = $_REQUEST["username"];
	$otkid = $_REQUEST["otkid"];
	$users = $myAC->getUsers();
	$realname = "";
	$otk = "";
	foreach($users as $user) {
		if($user["username"] == $username) {
			$realname = $user["realname"];
			$otk = $user["otk"];
		}
	}
	
	if($realname == "") $realname = $username;
	if($otk == "") {
?>
<html>
Hello <?php echo $realname?>, we're sorry, but your One Time Key has already been picked up or you
dont currently have a token. If you believe this in error, please contact the site admin immediately
as it could mean your key has been compromised.
</html>
<?php
	exit(0); 
	}
	
	if($otk != $otkid) {
?>
<html>
Hello <?php echo $realname?>, we're sorry, but your One Time Key ID is not
the correct one, the URL you have been sent may be in error, please check with the site admin
</html>
<?php	
	}
	
	// now actually pick up the key
	if(isset($_REQUEST["ready"])) {
?>
<html>
Hello <?php echo $realname?>, welcome to the One Time Key retreival site. Here is your<br>
One Time Key. Do not save this anywhere as it will compromise your account<br>
<li> Point your phones camera at the screen
<li> Watch the display until it locks onto the code
<li> Once the code has been scanned, the phone should return to the Google Authenticator with a 6 digit number presented, or a "get code" button.<br><hr>
<img src="?action=actuallygettoken&username=<?php echo $username?>&otkid=<?php echo $otkid ?>"><br>

Once you have the key, you may try logging into the user site <a href="index.php">here</a>
</html>
<?php 
	} else {
?>
<html>
Hello <?php echo $realname?>, welcome to the One Time Key retreival site. Before we present<br>
your key, you must have your phone ready to accept it as the key will only be presented once.<br>
If your phone is not ready to accept the key, the key needs to be regenerated, so only proceed<br>
if you phone is on, you have clicked on "scan account barcode" and the phone is ready to<br>
scan, please proceed.<br>
<br>
If you are ready to proceed, click <a href="index.php?gettoken&username=<?php echo $username?>&ready=true&otkid=<?php echo $otkid?>">here</a>.
</html>
<?php 
	}
	exit(0);
}


?>
<html>
<h1>Welcome to the GAAS User Site</h1>
<?php
if(isset($_REQUEST["message"])) {
	echo "<font color=\"green\">".$_REQUEST["message"]."</font>";
} 
if(isset($_REQUEST["error"])) {
	echo "<font color=\"red\">".$_REQUEST["error"]."</font>";
} 

if(!$loggedin) {
?>
<form method="post" action="?action=login">
Username: <input type="text" name="username"><br>
Token Code: <input type="text" name="tokencode"><br>
<input type="submit" value="Login">
</form>
</html>
<?php
	exit(0); 
} else {
?>

Hi user
</html>

<hr><a href="?action=logout">Logout</a>

<?php 
}
?>

