<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($user->isAuthenticated()) {
  // Reset failed login counter
  $user->setUserFailed($_SESSION['USERDATA']['id'], 0);
  if (!empty($_SERVER['HTTP_REFERER'])) header("Location: " . $_SERVER['HTTP_REFERER']);
}
// Somehow we still need to load this empty template
$smarty->assign("CONTENT", "empty");
?>
