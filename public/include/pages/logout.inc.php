<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($config['strict']) {
  $session->destroy_session($_SERVER['REMOTE_ADDR']);
  $user->logoutUser();
} else {
  $user->logoutUser();
}
$smarty->assign("CONTENT", "default.tpl");
?>
