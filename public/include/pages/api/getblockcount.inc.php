<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$id = $user->checkApiKey($_REQUEST['api_key']);

if ($bitcoin->can_connect() === true){
  if (!$iBlock = $memcache->get('iBlock')) {
    $iBlock = $bitcoin->query('getblockcount');
    $memcache->set('iBlock', $iBlock);
  }
} else {
  $iBlock = 0;
}

// Output JSON format
echo json_encode(array('getblockcount' => $iBlock));

// Supress master template
$supress_master = 1;
?>
