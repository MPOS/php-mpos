<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// MPOS has issues with version 5.7 so lets fetch this installs version
$mysql_version = $mysqli->query('SELECT VERSION() AS version')->fetch_object()->version;

// This should be set if we are running on 5.7
$mysql_mode = $mysqli->query('SELECT @@GLOBAL.sql_mode AS sql_mode')->fetch_object()->sql_mode;

// see if it includes 5.7
if (strpos($mysql_version, '5.7') !== false && strpos($mysql_mode, 'ONLY_FULL_GROUP_BY') !== false) {
	$newerror = array();
	$newerror['name'] = "MySQL Version";
	$newerror['level'] = 3;
	$newerror['description'] = "SQL version not fully supported.";
	$newerror['configvalue'] = "db.*";
	$newerror['extdesc'] = "You are using MySQL Version $mysql_version which is not fully supported. You may run into issues during payout when using this version of MySQL. Please see our Wiki FAQ on how to workaround any potential issues. This check only matches your version string against `5.7` so you may still be fine.";
	$newerror['helplink'] = "";
	$error[] = $newerror;
	$newerror = null;
}
