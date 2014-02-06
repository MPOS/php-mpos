<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if (isset($_POST['do']) && $_POST['do'] == 'resetPassword') {
  if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
    if ($user->resetPassword($_POST['token'], $_POST['newPassword'], $_POST['newPassword2'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Password reset complete! Please login.', 'TYPE' => 'success');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $user->getError(), 'TYPE' => 'errormsg');
    }
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'info');
  }
}

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");

?>
