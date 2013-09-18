<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$id = $user->checkApiKey($_REQUEST['api_key']);

// Fetch data from wallet
if ($bitcoin->can_connect() === true){
  $dDifficulty = $bitcoin->getdifficulty();
} else {
  $iDifficulty = 1;
}

// Output JSON format
echo json_encode(array('getdifficulty' => $dDifficulty));

// Supress master template
$supress_master = 1;
?>
