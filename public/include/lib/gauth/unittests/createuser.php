<?php
require_once("../lib/ga4php.php");

$dbfile = "/tmp/db.sqlite";

$ga = new GoogleAuthenticator($dbfile);

$oldkey = "";
$key = $ga->createBase32Key();

$url = $ga->setupUser("someuser", $key);

system("qrencode -s 6 -o /tmp/file.unittest $url");
system("eog /tmp/file.unittest");
unlink("/tmp/file.unittest");

?>
