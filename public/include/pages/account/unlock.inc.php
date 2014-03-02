<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Confirm an account by token
if (!isset($_GET['token']) || empty($_GET['token'])) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Missing token', 'TYPE' => 'alert alert-danger');
} else if (!$aToken = $oToken->getToken($_GET['token'], 'account_unlock')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to re-activate your account. Invalid token.', 'TYPE' => 'alert alert-danger');
} else {
  if ($user->setUserFailed($aToken['account_id'], 0) && $user->setUserPinFailed($aToken['account_id'], 0) && $user->setLocked($aToken['account_id'], 0)) {
    $oToken->deleteToken($aToken['token']);
    $_SESSION['POPUP'][] = array('CONTENT' => 'Account re-activated. Please login.');
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Failed to re-activate account. Contact site support.', 'TYPE' => 'alert alert-danger');
  }
}
$smarty->assign('CONTENT', 'default.tpl');

?>
