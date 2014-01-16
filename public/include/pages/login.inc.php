<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($setting->getValue('maintenance') && !$user->isAdmin($user->getUserId($_POST['username']))) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'You are not allowed to login during maintenace.', 'TYPE' => 'info');
} else if (isset($_POST['username']) && isset($_POST['password'])) {
  $nocsrf = 1;
  if ($config['csrf']['enabled'] && $config['csrf']['forms']['login']) {
    if ((isset($_POST['ctoken']) && $_POST['ctoken'] !== $user->getCSRFToken($_SERVER['REMOTE_ADDR'], 'login')) || (!isset($_POST['ctoken']))) {
      // csrf protection is on and this token is invalid, error out -> time expired
      $nocsrf = 0;
    }
  }
  if ($nocsrf == 1 || (!$config['csrf']['enabled'] || !$config['csrf']['forms']['login'])) {
    $checklogin = $user->checkLogin($_POST['username'], $_POST['password']);
    if ($checklogin) {
      empty($_POST['to']) ? $to = $_SERVER['SCRIPT_NAME'] : $to = $_POST['to'];
      $port = ($_SERVER["SERVER_PORT"] == "80" or $_SERVER["SERVER_PORT"] == "443") ? "" : (":".$_SERVER["SERVER_PORT"]);
      $location = @$_SERVER['HTTPS'] === true ? 'https://' . $_SERVER['SERVER_NAME'] . $port . $to : 'http://' . $_SERVER['SERVER_NAME'] . $port . $to;
      if (!headers_sent()) header('Location: ' . $location);
      exit('<meta http-equiv="refresh" content="0; url=' . htmlspecialchars($location) . '"/>');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to login: '. $user->getError(), 'TYPE' => 'errormsg');
    }
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to login: Token expired', 'TYPE' => 'errormsg');
  }
} else if (@$_POST['username'] && @$_POST['password']) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to login: '. $user->getError(), 'TYPE' => 'errormsg');
}
// csrf token - update if it's enabled
$token = '';
if ($config['csrf']['enabled'] && $config['csrf']['forms']['login']) {
  $token = $user->getCSRFToken($_SERVER['REMOTE_ADDR'], 'login');
}
// Load login template
$smarty->assign('CONTENT', 'default.tpl');
$smarty->assign('CTOKEN', $token);
?>
