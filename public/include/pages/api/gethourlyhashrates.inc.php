<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $user->checkApiKey($_REQUEST['api_key']);

if ( ! $user->isAdmin($user_id) && ($_REQUEST['id'] != $user_id && !empty($_REQUEST['id']))) {
  // User is admin and tries to access an ID that is not their own
  header("HTTP/1.1 401 Unauthorized");
  die("Access denied");
} else if ($user->isAdmin($user_id)) {
  // Is it a username or a user ID
  ctype_digit($_REQUEST['id']) ? $id = $_REQUEST['id'] : $id = $user->getUserId($_REQUEST['id']);
} else {
  // Not admin, only allow own user ID
  $id = $user_id;
}

// Output JSON format
echo json_encode(array('gethourlyhashrates' => array(
  'mine' => $statistics->getHourlyHashrateByAccount($id),
  'pool' => $statistics->getHourlyHashrateByPool()
)), JSON_FORCE_OBJECT);

// Supress master template
$supress_master = 1;
?>
