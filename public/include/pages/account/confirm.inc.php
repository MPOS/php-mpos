<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Confirm an account by token
if (!isset($_GET['token']) || empty($_GET['token'])) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Missing token', 'TYPE' => 'errormsg');
} else if (!$aToken = $oToken->getToken($_GET['token'], 'confirm_email')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to activate your account. Invalid token.', 'TYPE' => 'errormsg');
} else {
  $user->setLocked($aToken['account_id'], 0);
  $oToken->deleteToken($aToken['token']);
  $_SESSION['POPUP'][] = array('CONTENT' => 'Account activated. Please login.');
}
$smarty->assign('CONTENT', 'default.tpl');
?>
