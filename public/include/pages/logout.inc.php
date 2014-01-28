<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($config['strict']) {
  $user->logoutUser();
  $update = $session::$client_model;
  $update['sid'] = session_id();
  $update['ua'] = $_SERVER['HTTP_USER_AGENT'];
  $update['ip'] = $_SERVER['REMOTE_ADDR'];
  $update['la'] = time();
  $update['key'] = md5($update['ua'].$update['ip']);
  $session->create_or_update_client($update, true);
} else {
  $user->logoutUser();
}

$smarty->assign("CONTENT", "default.tpl");
?>
