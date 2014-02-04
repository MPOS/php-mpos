<?php

require_once(INCLUDE_DIR."/lib/GoogleAuthenticator/GoogleAuthenticator.php");

$GAuth = new GoogleAuthenticator();
$GAuth->setDebug($debug);
$GAuth->setMysql($mysqli);
$GAuth->setSalt($config['SALT']);
$GAuth->setConfig($config);
$GAuth->setSetting($setting);
?>
