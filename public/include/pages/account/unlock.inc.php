<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// csrf stuff
$csrfenabled = ($config['csrf']['enabled'] && !in_array('unlockaccount', $config['csrf']['disabled_forms'])) ? 1 : 0;
if ($csrfenabled) {
  $nocsrf = ($csrftoken->getBasic($user->getCurrentIP(), 'unlockaccount') == @$_POST['ctoken']) ? 1 : 0;
}

// Confirm an account by token
if (!isset($_GET['token']) || empty($_GET['token'])) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Missing token', 'TYPE' => 'errormsg');
} else if (!$aToken = $oToken->getToken($_GET['token'], 'account_unlock')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to re-activate your account. Invalid token.', 'TYPE' => 'errormsg');
} else {
  if (!$csrfenabled || $csrfenabled && !$nocsrf) {
    if ($user->setUserFailed($aToken['account_id'], 0) && $user->setUserPinFailed($aToken['account_id'], 0) && $user->changeLocked($aToken['account_id'])) {
      $oToken->deleteToken($aToken['token']);
      $_SESSION['POPUP'][] = array('CONTENT' => 'Account re-activated. Please login.');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Failed to re-activate account. Contact site support.', 'TYPE' => 'errormsg');
    }
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'info');
  }
}
// csrf token
if ($csrfenabled && !in_array('unlockaccount', $config['csrf']['disabled_forms'])) {
  $token = $csrftoken->getBasic($user->getCurrentIP(), 'unlockaccount');
  $smarty->assign('CTOKEN', $token);
}
$smarty->assign('CONTENT', 'default.tpl');
?>
