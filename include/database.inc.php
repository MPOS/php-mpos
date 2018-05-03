<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Instantiate class, we are using mysqlng
if ($config['mysql_filter']) {
  $mysqli = new mysqlims($config['db'],$config['db-ro'], true);
} else {
  $mysqli = new mysqlims($config['db'],$config['db-ro'], false);
}

// Check if read-only and quit if it is on, disregard if slave is enabled

if ($mysqli->query('/* MYSQLND_MS_MASTER_SWITCH */SELECT @@global.read_only AS read_only')->fetch_object()->read_only == 1 && $config['db-ro']['enabled'] === false ) {
  die('Database is in READ-ONLY mode');
}

/* check connection */
if (mysqli_connect_errno()) {
  die("Failed to connect to database");
}
