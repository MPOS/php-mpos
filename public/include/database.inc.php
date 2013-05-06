<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Instantiate class, we are using mysqli
$mysqli = new mysqli($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['name']);

/* check connection */
if (mysqli_connect_errno()) {
  $debug->append("Failed to connect to database as non fatal error", 1);
}

/* Example for a query
  $query = $mysqli->prepare("SELECT CountryCode, Percentage FROM Language WHERE Language=?");
  $lang = "English";
  $query->bind_param("s", $lang);
  $query->execute();
  $query->bind_result($countrycode, $percentage);
  while ($query->fetch()) {
  printf("%s lang is in CountryCode %s with Percentage %s\n", $lang, $countrycode, $percentage);
  }
  $query->close();
 */
?>
