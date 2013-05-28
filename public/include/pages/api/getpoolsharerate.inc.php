<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
die('Hacking attempt');

// Check user token
$user->checkApiKey($_REQUEST['api_key']);

// Output JSON format
echo json_encode(array('getpoolsharerate' => $statistics->getCurrentShareRate()));

// Supress master template
$supress_master = 1;
?>
