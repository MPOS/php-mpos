<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Output JSON format
$data = array(
  'mine' => $statistics->getHourlyHashrateByAccount($id),
  'pool' => $statistics->getHourlyHashrateByPool()
);

echo $api->json($data);

// Supress master template
$supress_master = 1;
?>
