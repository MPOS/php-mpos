<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($setting->getValue('maintenance') && !$user->isAdmin($user->getUserId($_POST['username']))) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'You are not allowed to login during maintenace.', 'TYPE' => 'info');
} else if ($user->checkLogin(@$_POST['username'], @$_POST['password']) ) {
  empty($_POST['to']) ? $to = $_SERVER['PHP_SELF'] : $to = $_POST['to'];
  $location = @$_SERVER['HTTPS'] === true ? 'https' : 'http' . '://' . $_SERVER['SERVER_NAME'] . $to;
  if (!headers_sent()) header('Location: ' . $location);
  exit('<meta http-equiv="refresh" content="0; url=' . $location . '"/>');
} else if (@$_POST['username'] && @$_POST['password']) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to login: '. $user->getError(), 'TYPE' => 'errormsg');
}

// Load login template
$smarty->assign('CONTENT', 'default.tpl');
?>
