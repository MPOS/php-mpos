<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
die('Hacking attempt');

// Check user token
$id = $user->checkApiKey($_REQUEST['api_key']);

// We have to check if that user is admin too
if ( ! $user->isAdmin($id) ) {
  header("HTTP/1.1 401 Unauthorized");
  die("Access denied");
}

// Is it a username or a user ID
ctype_digit($_REQUEST['id']) ? $username = $user->getUserName($_REQUEST['id']) : $username = $_REQUEST['id'];
ctype_digit($_REQUEST['id']) ? $id = $_REQUEST['id'] : $id = $user->getUserId($_REQUEST['id']);

// Output JSON format
echo json_encode(array('getuserstatus' => array(
  'username' => $username,
  'shares' =>  $statistics->getUserShares($id),
  'hashrate' => $statistics->getUserHashrate($id)
)));

// Supress master template
$supress_master = 1;
?>
