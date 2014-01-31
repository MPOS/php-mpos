<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Instantiate class, we are using mysqlng
if ($config['mysql_filter']) {
  $mysqli = new mysqli_strict($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['name'], $config['db']['port']);
} else {
  $mysqli = new mysqli($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['name'], $config['db']['port']);
}

// Check if read-only and quit if it is on
if ($mysqli->query('/* MYSQLND_MS_MASTER_SWITCH */SELECT @@global.read_only AS read_only')->fetch_object()->read_only == 1) {
  die('Database is in READ-ONLY mode');
}

/* check connection */
if (mysqli_connect_errno()) {
  die("Failed to connect to database");
}

?>
