<?php

require_once("../lib/ga4php.php");

$ga = new GoogleAuthenticator("/dev/null");

echo "creating 10000 keys\n";
$oldkey = "";
$key = $ga->createBase32Key();

$hex = $ga->helperb322hex($key);

$url = $ga->createURL("someuser", $key);

system("qrencode -s 6 -o /tmp/file.unittest $url");
system("eog /tmp/file.unittest");
echo "key in hex: $hex\n";
//unlink("/tmp/file.unittest");
?>
