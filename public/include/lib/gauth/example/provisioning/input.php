<?php

// this part of the example is the part that processes user inputs from forms
function processInput() {
	global $myga;
	
	if(isset($_REQUEST["action"])) {
		switch($_REQUEST["action"]) {
			case "createuser":
				// "users_id" INTEGER PRIMARY KEY AUTOINCREMENT,"users_username" TEXT,"users_fullname" TEXT,"users_tokendata" TEXT
				$username = $_REQUEST["username"];
				$fullname = $_REQUEST["fullname"];
				$password = sha1($_REQUEST["password"]);
				$sql = "insert into users values (NULL, '$username', '$fullname', '$password','0')";
				$db = getDatabase();
				$db->query($sql);
				closeDatabase($db);
				
				header("Location: index.php?success=created");
				break;
			case "provision":
				$username = $_REQUEST["user"];
				$tokentype = $_REQUEST["tokentype"];
				$myga->setUser($username, $tokentype);
				
				header("Location: index.php?success=Provisioned");
				break;
			case "auth":
				$username = $_REQUEST["user"];
				$tokencode = $_REQUEST["tokencode"];
				
				if($myga->authenticateUser($username, $tokencode)) {
					header("Location: index.php?success=Passed");
				} else {
					header("Location: index.php?failure=wrongcode");
				}
				break;
		}
	}
}
?>