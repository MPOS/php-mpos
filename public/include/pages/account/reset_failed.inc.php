<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($user->isAuthenticated()) {
  // Reset failed login counter
  $user->setUserFailed($_SESSION['USERDATA']['id'], 0);
  $port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : (":".$_SERVER["SERVER_PORT"]);
  $pushto = $_SERVER['SCRIPT_NAME'].'?page=dashboard';
  $location = (@$_SERVER['HTTPS'] == 'on') ? 'https://' . $_SERVER['SERVER_NAME'] . $port . $pushto : 'http://' . $_SERVER['SERVER_NAME'] . $port . $pushto;
  header("Location: " . $location);
}
// Somehow we still need to load this empty template
$smarty->assign("CONTENT", "empty");
?>
