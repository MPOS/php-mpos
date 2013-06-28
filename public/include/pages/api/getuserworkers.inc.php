<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
die('Hacking attempt');

// Check user token
$user_id = $user->checkApiKey($_REQUEST['api_key']);

// We have to check if that user is admin too
if ( ! $user->isAdmin($user_id) && ($_REQUEST['id'] != $user_id && !empty($_REQUEST['id']))) {
  header("HTTP/1.1 401 Unauthorized");
  die("Access denied");
} else if ($user->isAdmin($user_id)) {
  $id = $_REQUEST['id'];
  ctype_digit($_REQUEST['id']) ? $id = $_REQUEST['id'] : $id = $user->getUserId($_REQUEST['id']);
} else {
  $id = $user_id;
}

// Output JSON format
echo json_encode(array('getuserworkers' => $worker->getWorkers($id)));

// Supress master template
$supress_master = 1;
?>
