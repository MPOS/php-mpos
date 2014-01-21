<?php

function getDatabase() {
	$dbobject = false;
	if(file_exists("/tmp/gadata.sqlite")) {
		try {
			$dbobject = new PDO("sqlite:/tmp/gadata.sqlite");
		} catch(PDOException $exep) {
			error_log("execpt on db open");
		}
	} else {
		try {
			$dbobject = new PDO("sqlite:/tmp/gadata.sqlite");
		} catch(PDOException $exep) {
			error_log("execpt on db open");
		}
		$sql = 'CREATE TABLE "users" ("users_id" INTEGER PRIMARY KEY AUTOINCREMENT,"users_username" TEXT,"users_fullname" TEXT,"users_password" TEXT, "users_tokendata" TEXT);';
		$dbobject->query($sql);
	}
	
	return $dbobject;
}

function closeDatabase($db) {
	// doesnt do anything yet
}
?>