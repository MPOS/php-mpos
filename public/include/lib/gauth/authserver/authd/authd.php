<?php

// TODO: SO MUCH ERROR CHECKING ITS NOT FUNNY


// get out master library for ga4php
require_once("../lib/lib.php");

	
//exit(0);
// first we want to fork into the background like all good daemons should
//$pid = pcntl_fork();
$pid = 0;

if($pid == -1) {
	
} else if($pid) {
	// i am the parent, i shall leave
	echo "i am a parent, i leave\n";
	exit(0);
} else {
	global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;
	
	$cl_queue = msg_get_queue($MSG_QUEUE_KEY_ID_CLIENT, 0666 | 'IPC_CREAT');
	$sr_queue = msg_get_queue($MSG_QUEUE_KEY_ID_SERVER, 0666 | 'IPC_CREAT');

	$myga = new gaasGA();
	global $myga;
	
	
	while(true) {
		msg_receive($sr_queue, 0, $msg_type, 16384, $msg);
		switch($msg_type) {
			case MSG_DELETE_USER_TOKEN:
				$username = $msg["username"];
				
				$sql = "select users_otk from users where users_username='$username'";
				$dbo = getDatabase();
				$res = $dbo->query($sql);
				$otkid = "";
				foreach($res as $row) {
					$otkid = $row["users_otk"];
				}
				if($otkid!="") {
					global $BASE_DIR;
					unlink("$BASE_DIR/authserver/authd/otks/$otkid.png");
				}
				
				$sql = "update users set users_tokendata='',users_otk='' where users_username='$username'";
				$dbo = getDatabase();
				$res = $dbo->query($sql);
				
				msg_send($cl_queue, MSG_DELETE_USER_TOKEN, true);
				break;
			case MSG_AUTH_USER_TOKEN:
				echo "Call to auth user token\n";
				// minimal checking, we leav it up to authenticateUser to do the real
				// checking
				if(!isset($msg["username"])) $msg["username"] = "";
				if(!isset($msg["passcode"])) $msg["passcode"] = "";
				$username = $msg["username"];
				$passcode = $msg["passcode"];
				global $myga;
				$authval = $myga->authenticateUser($username, $passcode);
				msg_send($cl_queue, MSG_AUTH_USER_TOKEN, $authval);
				break;
			case MSG_GET_OTK_ID:
				if(!isset($msg["username"])) {
					msg_send($cl_queue, MSG_GET_OTK_ID, false);
				} else {
					$username = $msg["username"];
					$sql = "select users_otk from users where users_username='$username'";
					$dbo = getDatabase();
					$res = $dbo->query($sql);
					$otkid = "";
					foreach($res as $row) {
						$otkid = $row["users_otk"];
					}
					
					if($otkid == "") {
						msg_send($cl_queue, MSG_GET_OTK_ID, false);
					} else {
						msg_send($cl_queue, MSG_GET_OTK_ID, $otkid);
					}
				}
				break;
			case MSG_GET_OTK_PNG:
				if(!isset($msg["otk"])) {
					msg_send($cl_queue, MSG_GET_OTK_PNG, false);
				} else {
					$otk = $msg["otk"];
					$sql = "select users_username from users where users_otk='$otk'";
					$dbo = getDatabase();
					$res = $dbo->query($sql);
					$username = "";
					foreach($res as $row) {
						$username = $row["users_username"];
					}
					
					if($username == "") {
						msg_send($cl_queue, MSG_GET_OTK_PNG, false);
					} else if($username != $msg["username"]) {
						msg_send($cl_queue, MSG_GET_OTK_PNG, false);
					} else {
						global $BASE_DIR;
						$hand = fopen("$BASE_DIR/authserver/authd/otks/$otk.png", "rb");
						$data = fread($hand, filesize("$BASE_DIR/authserver/authd/otks/$otk.png"));
						fclose($hand);
						unlink("$BASE_DIR/authserver/authd/otks/$otk.png");
						$sql = "update users set users_otk='' where users_username='$username'";
						$dbo->query($sql);
						error_log("senting otk, fsize: ".filesize("$BASE_DIR/authserver/authd/otks/$otk.png")." $otk ");
						msg_send($cl_queue, MSG_GET_OTK_PNG, $data);
					}
				}
				
				break;
			case MSG_ADD_USER_TOKEN:
				echo "Call to add user token\n";
				if(!isset($msg["username"])) {
					msg_send($cl_queue, MSG_ADD_USER_TOKEN, false);	
				} else {
					global $BASE_DIR;
					$username = $msg["username"];
					$tokentype="TOTP";
					if(isset($msg["tokentype"])) {
						$tokentype=$msg["tokentype"];
					}
					$hexkey = "";
					if(isset($msg["hexkey"])) {
						$hexkey = $msg["hexkey"];
					}
					global $myga;
					$myga->setUser($username, $tokentype, "", $hexkey);
					
					$url = $myga->createUrl($username);
					if(!file_exists("$BASE_DIR/authserver/authd/otks")) mkdir("$BASE_DIR/authserver/authd/otks");
					$otk = generateRandomString();
					system("qrencode -o $BASE_DIR/authserver/authd/otks/$otk.png $url");
					
					$sql = "update users set users_otk='$otk' where users_username='$username'";
					$dbo = getDatabase();
					$res = $dbo->query($sql);
					
					msg_send($cl_queue, MSG_ADD_USER_TOKEN, true);
				}
				break;
			case MSG_DELETE_USER:
				echo "Call to del user\n";
				if(!isset($msg["username"])) {
					msg_send($cl_queue, MSG_DELETE_USER, false);	
				} else {
					$username = $msg["username"];				
					global $myga;

					$sql = "select users_otk from users where users_username='$username'";
					$dbo = getDatabase();
					$res = $dbo->query($sql);
					$otkid = "";
					foreach($res as $row) {
						$otkid = $row["users_otk"];
					}
					if($otkid!="") {
						unlink("otks/$otkid.png");
					}
					

					$sql = "delete from users where users_username='$username'";
					$dbo = getDatabase();
					$dbo->query($sql);

					msg_send($cl_queue, MSG_DELETE_USER, true);
				}
				break;
			case MSG_AUTH_USER_PASSWORD:
				// TODO
				echo "Call to auth user pass\n";
				if(!isset($msg["username"])) {
					msg_send($cl_queue, MSG_AUTH_USER_PASSWORD, false);
					break;
				}
				if(!isset($msg["password"])) {
					msg_send($cl_queue, MSG_AUTH_USER_PASSWORD, false);
					break;
				}
				
				$username = $msg["username"];
				$password = $msg["password"];
				$sql = "select users_password from users where users_username='$username'";
				$dbo = getDatabase();
				$res = $dbo->query($sql);
				$pass = "";
				foreach($res as $row) {
					$pass = $row["users_password"];
				}
				
				// TODO now do auth
				$ourpass = hash('sha512', $password);
				echo "ourpass: $ourpass\nourhash: $pass\n";
				if($ourpass == $pass) {
					msg_send($cl_queue, MSG_AUTH_USER_PASSWORD, true);
					
				} else {
					msg_send($cl_queue, MSG_AUTH_USER_PASSWORD, false);
					
				}
				
				break;
			case MSG_SET_USER_PASSWORD:
				echo "how on earth is that happening Call to set user pass, wtf?\n";
				// TODO
				print_r($msg);
				if(!isset($msg["username"])) {
					msg_send($cl_queue, MSG_SET_USER_PASSWORD, false);
					echo "in break 1\n";
					break;
				}
				if(!isset($msg["password"])) {
					msg_send($cl_queue, MSG_SET_USER_PASSWORD, false);
					echo "in break 1\n";
					break;
				}
				
				$username = $msg["username"];
				$password = $msg["password"];
				
				echo "would set pass for $username, to $password\n";
				if($password == "") $pass = "";
				else $pass = hash('sha512', $password);
				
				$dbo = getDatabase();
				echo "in set user pass for $username, $pass\n";
				$sql = "update users set users_password='$pass' where users_username='$username'";
				
				$dbo->query($sql);

				msg_send($cl_queue, MSG_SET_USER_REALNAME, true);
				
				
				// these are irrelavent yet
				// TODO now set pass
				break;
			case MSG_SET_USER_REALNAME:
				echo "Call to set user realname\n";
				// TODO
				if(!isset($msg["username"])) {
					msg_send($cl_queue, MSG_SET_USER_REALNAME, false);
					break;
				}
				if(!isset($msg["realname"])) {
					msg_send($cl_queue, MSG_SET_USER_REALNAME, false);
					break;
				}
				
				$username = $msg["username"];
				$realname = $msg["realname"];
				$sql = "update users set users_realname='$realname' where users_username='$username'";
				$dbo = getDatabase();
				
				$dbo->query($sql);

				msg_send($cl_queue, MSG_SET_USER_REALNAME, true);
				
				// TODO now set real name
				break;
			case MSG_SET_USER_TOKEN:
				// TODO
				echo "Call to set user token\n";
				if(!isset($msg["username"])) {
					msg_send($cl_queue, MSG_SET_USER_TOKEN, false);
					break;
				}
				if(!isset($msg["tokenstring"])) {
					msg_send($cl_queue, MSG_SET_USER_TOKEN, false);
					break;
				}
				
				global $myga;
				$username = $msg["username"];
				$token = $msg["tokenstring"];
				$return = $myga->setUserKey($username, $token);
				msg_send($cl_queue, MSG_SET_USER_TOKEN, $return);
				
				// TODO now set token 
				break;			
			case MSG_SET_USER_TOKEN_TYPE:
				// TODO
				echo "Call to set user token type\n";
				if(!isset($msg["username"])) {
					msg_send($cl_queue, MSG_SET_USER_TOKEN_TYPE, false);
					break;
				}
				if(!isset($msg["tokentype"])) {
					msg_send($cl_queue, MSG_SET_USER_TOKEN_TYPE, false);
					break;
				}
				
				$username = $msg["username"];
				$tokentype = $msg["tokentype"];
				global $myga;
				msg_send($cl_queue, MSG_SET_USER_TOKEN_TYPE, $myga->setTokenType($username, $tokentype));
				
				// TODO now set token 
				break;
			case MSG_GET_USERS:
				// TODO this needs to be better
				$sql = "select * from users";
				
				$dbo = getDatabase();
				$res = $dbo->query($sql);
				
				$users = "";
				$i = 0;
				foreach($res as $row) {
					$users[$i]["username"] = $row["users_username"];
					$users[$i]["realname"] = $row["users_realname"];
					if($row["users_password"]!="") {
						$users[$i]["haspass"] = true;
					} else {
						$users[$i]["haspass"] = false;
					}
					echo "user: ".$users[$i]["username"]." has tdata: \"".$row["users_tokendata"]."\"\n";
					if($row["users_tokendata"]!="") {
						$users[$i]["hastoken"] = true;
					} else {
						$users[$i]["hastoken"] = false;
					}
					
					if($row["users_otk"]!="") {
						$users[$i]["otk"] = $row["users_otk"];
					} else {
						$users[$i]["otk"] = "";
					}
					$i++; 
				}
				msg_send($cl_queue, MSG_GET_USERS, $users);
				
				// TODO now set token 
				break;
				
		}		
	}	
}

?>
