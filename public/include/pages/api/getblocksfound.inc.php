<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$id = $user->checkApiKey($_REQUEST['api_key']);

// Set a sane limit, overwrite with URL parameter
$iLimit = 10;
if (@$_REQUEST['limit'])
  $iLimit = $_REQUEST['limit'];

// Output JSON format
echo json_encode(array('getblocksfound' => $statistics->getBlocksFound($iLimit)));

// Supress master template
$supress_master = 1;
?>
