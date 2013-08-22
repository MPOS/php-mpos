<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $user->checkApiKey($_REQUEST['api_key']);

echo $user_id;

// We have to check if that user is admin too
if ( ! $user->isAdmin($user_id) && ($_REQUEST['id'] != $user_id && !empty($_REQUEST['id']))) {
  header("HTTP/1.1 401 Unauthorized");
  die("Access denied");
} else if ($user->isAdmin($user_id) && !empty($_REQUEST['id'])) {
  $id = $_REQUEST['id'];
  ctype_digit($_REQUEST['id']) ? $id = $_REQUEST['id'] : $id = $user->getUserId($_REQUEST['id']);
} else {
  $id = $user_id;
}

// Output JSON format
echo json_encode(array('getuserbalance' => $transaction->getBalance($id)));

// Supress master template
$supress_master = 1;
?>
