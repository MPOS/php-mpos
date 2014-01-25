<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($user->isAuthenticated()) {
  // Reset failed login counter
  $user->setUserFailed($_SESSION['USERDATA']['id'], 0);
  header("Location: " . $_SERVER['HTTP_REFERER']);
}
// Somehow we still need to load this empty template
$smarty->assign("CONTENT", "empty");
?>
