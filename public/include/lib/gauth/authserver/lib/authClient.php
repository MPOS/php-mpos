<?php

require_once("lib.php");

class GAAuthClient {
	
	function setUserToken($username, $token) {
		global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;
		
		
		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_SERVER)) {
			return false;
		}

		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_CLIENT)) {
			return false;
		}
		// TODO we need to setup a client queue sem lock here
		
		$cl_queue = msg_get_queue($MSG_QUEUE_KEY_ID_CLIENT);
		$sr_queue = msg_get_queue($MSG_QUEUE_KEY_ID_SERVER);
		
		$message["username"] = $username;
		$message["tokenstring"] = $token;
		
		msg_send($sr_queue, MSG_SET_USER_TOKEN, $message, true, true, $msg_err);
		
		msg_receive($cl_queue, 0, $msg_type, 16384, $msg);
		return $msg;		
	}
	
	function setUserPass($username, $password) {
		global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;
		
		
		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_SERVER)) {
			return false;
		}

		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_CLIENT)) {
			return false;
		}
		// TODO we need to setup a client queue sem lock here
		
		$cl_queue = msg_get_queue($MSG_QUEUE_KEY_ID_CLIENT);
		$sr_queue = msg_get_queue($MSG_QUEUE_KEY_ID_SERVER);

		$message["username"] = $username;
		$message["password"] = $password;
		
		msg_send($sr_queue, MSG_SET_USER_PASSWORD, $message, true, true, $msg_err);
		
		msg_receive($cl_queue, 0, $msg_type, 16384, $msg);

		return $msg;
	}
	
	function getOtkID($username) {
		global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;
		
		
		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_SERVER)) {
			return false;
		}

		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_CLIENT)) {
			return false;
		}
		// TODO we need to setup a client queue sem lock here
		
		$cl_queue = msg_get_queue($MSG_QUEUE_KEY_ID_CLIENT);
		$sr_queue = msg_get_queue($MSG_QUEUE_KEY_ID_SERVER);

		$message["username"] = $username;
		msg_send($sr_queue, MSG_GET_OTK_ID, $message, true, true, $msg_err);
		
		msg_receive($cl_queue, 0, $msg_type, 16384, $msg);
		
		return $msg;
		
	}
	
	function getOtkPng($username, $otk) {
		global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;
		
		
		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_SERVER)) {
			return false;
		}

		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_CLIENT)) {
			return false;
		}
		// TODO we need to setup a client queue sem lock here
		
		$cl_queue = msg_get_queue($MSG_QUEUE_KEY_ID_CLIENT);
		$sr_queue = msg_get_queue($MSG_QUEUE_KEY_ID_SERVER);

		$message["otk"] = $otk;
		$message["username"] = $username;
		error_log("sending message, $otk");
		msg_send($sr_queue, MSG_GET_OTK_PNG, $message, true, true, $msg_err);
		
		msg_receive($cl_queue, 0, $msg_type, 16384, $msg);
		
		return $msg;
		
	}
	
	function authUserPass($username, $password) {
		global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;
		
		
		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_SERVER)) {
			return false;
		}

		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_CLIENT)) {
			return false;
		}
		// TODO we need to setup a client queue sem lock here
		
		$cl_queue = msg_get_queue($MSG_QUEUE_KEY_ID_CLIENT);
		$sr_queue = msg_get_queue($MSG_QUEUE_KEY_ID_SERVER);

		$message["username"] = $username;
		$message["password"] = $password;
		
		msg_send($sr_queue, MSG_AUTH_USER_PASSWORD, $message, true, true, $msg_err);
		
		msg_receive($cl_queue, 0, $msg_type, 16384, $msg);
		return $msg;		
	}
	
	function deleteUser($username) {
		global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;
		
		
		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_SERVER)) {
			return false;
		}

		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_CLIENT)) {
			return false;
		}
		// TODO we need to setup a client queue sem lock here
		
		$cl_queue = msg_get_queue($MSG_QUEUE_KEY_ID_CLIENT);
		$sr_queue = msg_get_queue($MSG_QUEUE_KEY_ID_SERVER);

		$message["username"] = $username;
		
		msg_send($sr_queue, MSG_DELETE_USER, $message, true, true, $msg_err);
		
		msg_receive($cl_queue, 0, $msg_type, 16384, $msg);
		
		return $msg;
		
	}
	
	function setUserRealName($username, $realname) {
		global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;
		
		
		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_SERVER)) {
			return false;
		}

		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_CLIENT)) {
			return false;
		}
		// TODO we need to setup a client queue sem lock here
		
		$cl_queue = msg_get_queue($MSG_QUEUE_KEY_ID_CLIENT);
		$sr_queue = msg_get_queue($MSG_QUEUE_KEY_ID_SERVER);

		$message["username"] = $username;
		$message["realname"] = $realname;
		
		msg_send($sr_queue, MSG_SET_USER_REALNAME, $message, true, true, $msg_err);
		
		msg_receive($cl_queue, 0, $msg_type, 16384, $msg);
		return $msg;		
	}
	
	function getUsers() {
		global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;
		
		
		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_SERVER)) {
			return false;
		}

		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_CLIENT)) {
			return false;
		}
		// TODO we need to setup a client queue sem lock here
		
		$cl_queue = msg_get_queue($MSG_QUEUE_KEY_ID_CLIENT);
		$sr_queue = msg_get_queue($MSG_QUEUE_KEY_ID_SERVER);
		
		msg_send($sr_queue, MSG_GET_USERS, "", true, true, $msg_err);
		
		msg_receive($cl_queue, 0, $msg_type, 524288, $msg);
		
		return $msg;
	}
	
	function authUserToken($username, $passcode) {
		global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;
		
		
		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_SERVER)) {
			return false;
		}

		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_CLIENT)) {
			return false;
		}
		// TODO we need to setup a client queue sem lock here
		
		$cl_queue = msg_get_queue($MSG_QUEUE_KEY_ID_CLIENT);
		$sr_queue = msg_get_queue($MSG_QUEUE_KEY_ID_SERVER);
		
		
		$message["username"] = $username;
		$message["passcode"] = $passcode;
		
		msg_send($sr_queue, MSG_AUTH_USER_TOKEN, $message, true, true, $msg_err);
		
		msg_receive($cl_queue, 0, $msg_type, 16384, $msg);
		
		return $msg;
	}
	
	function deleteUserToken($username) {
		
		global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;
		
		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_SERVER)) {
			return false;
		}

		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_CLIENT)) {
			return false;
		}
		
		// TODO we need to setup a client queue sem lock here
		
		$cl_queue = msg_get_queue($MSG_QUEUE_KEY_ID_CLIENT);
		$sr_queue = msg_get_queue($MSG_QUEUE_KEY_ID_SERVER);
		
		
		$message["username"] = $username;
		
		msg_send($sr_queue, MSG_DELETE_USER_TOKEN, $message, true, true, $msg_err);
		
		msg_receive($cl_queue, 0, $msg_type, 16384, $msg);
		
		return $msg;
	}
	
	function addUser($username, $tokentype="", $hexkey="") {
		global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;
		
		
		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_SERVER)) {
			return false;
		}

		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_CLIENT)) {
			return false;
		}
		
		// TODO we need to setup a client queue sem lock here
		
		$cl_queue = msg_get_queue($MSG_QUEUE_KEY_ID_CLIENT);
		$sr_queue = msg_get_queue($MSG_QUEUE_KEY_ID_SERVER);
		
		
		$message["username"] = $username;
		if($tokentype!="") $message["tokentype"] = $tokentype;
		if($hexkey!="") $message["hexkey"] = $hexkey;
		
		msg_send($sr_queue, MSG_ADD_USER_TOKEN, $message, true, true, $msg_err);
		
		msg_receive($cl_queue, 0, $msg_type, 16384, $msg);
		
		return $msg;
	}

	function setUserTokenType($username, $tokentype) {
		global $MSG_QUEUE_KEY_ID_SERVER, $MSG_QUEUE_KEY_ID_CLIENT;
		
		
		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_SERVER)) {
			return false;
		}

		if(!msg_queue_exists($MSG_QUEUE_KEY_ID_CLIENT)) {
			return false;
		}
		
		// TODO we need to setup a client queue sem lock here
		
		$cl_queue = msg_get_queue($MSG_QUEUE_KEY_ID_CLIENT);
		$sr_queue = msg_get_queue($MSG_QUEUE_KEY_ID_SERVER);
		
		
		
		$message["username"] = $username;
		$message["tokentype"] = $tokentype;
		
		msg_send($sr_queue, MSG_SET_USER_TOKEN_TYPE, $message, true, true, $msg_err);
		
		msg_receive($cl_queue, 0, $msg_type, 16384, $msg);
		
		return $msg;
		
	}
}

?>
