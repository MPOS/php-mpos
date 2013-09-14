<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $user->checkApiKey($_REQUEST['api_key']);

/**
 * This check will ensure the user can do the following:
 * Admin: Check any user via request id
 * Regular: Check your own status
 * Other: Deny access via checkApiKey
 **/
if ( ! $user->isAdmin($user_id) && ($_REQUEST['id'] != $user_id && !empty($_REQUEST['id']))) {
  // User is admin and tries to access an ID that is not their own
  header("HTTP/1.1 401 Unauthorized");
  die("Access denied");
} else if ($user->isAdmin($user_id)) {
  // Admin, so allow any ID passed in request
  $id = $_REQUEST['id'];
  // Is it a username or a user ID
  ctype_digit($_REQUEST['id']) ? $username = $user->getUserName($_REQUEST['id']) : $username = $_REQUEST['id'];
  ctype_digit($_REQUEST['id']) ? $id = $_REQUEST['id'] : $id = $user->getUserId($_REQUEST['id']);
} else {
  // Not admin, only allow own user ID
  $id = $user_id;
  $username = $user->getUserName($id);
}

// Gather un-cached data
$statistics->setGetCache(false);
$start = microtime(true);
$hashrate = $statistics->getUserHashrate($id, 300);
$end = microtime(true);
$runtime = ($end - $start)* 1000;

// Output JSON format
echo json_encode(array('getuserhashrate' => array(
  'username' => $username,
  'runtime' => $runtime,
  'hashrate' => $hashrate
)));
$statistics->setGetCache(true);

// Supress master template
$supress_master = 1;
?>
