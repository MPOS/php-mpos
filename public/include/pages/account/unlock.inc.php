<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Confirm an account by token
if (!isset($_GET['token']) || empty($_GET['token'])) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Missing token', 'TYPE' => 'errormsg');
} else if (!$aToken = $oToken->getToken($_GET['token'], 'account_unlock')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to re-activate your account. Invalid token.', 'TYPE' => 'errormsg');
} else {
  if ($user->setUserFailed($aToken['account_id'], 0) && $user->setUserPinFailed($aToken['account_id'], 0) && $user->changeLocked($aToken['account_id'])) {
    $oToken->deleteToken($aToken['token']);
    $_SESSION['POPUP'][] = array('CONTENT' => 'Account re-activated. Please login.');
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Failed to re-activate account. Contact site support.', 'TYPE' => 'errormsg');
  }
}
$smarty->assign('CONTENT', 'default.tpl');
?>
