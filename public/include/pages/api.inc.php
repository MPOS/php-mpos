<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Check for valid API key
$user->checkApiKey($_REQUEST['api_key']);

header('HTTP/1.1 400 Bad Request');
die('400 Bad Request');
?>
