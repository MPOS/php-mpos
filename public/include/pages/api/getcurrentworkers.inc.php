<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
die('Hacking attempt');

// Check user token
$id = $user->checkApiKey($_REQUEST['api_key']);

// Output JSON format
echo json_encode(array('getcurrentworkers' => $worker->getCountAllActiveWorkers()));

// Supress master template
$supress_master = 1;
?>
