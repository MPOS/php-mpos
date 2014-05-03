<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Process password reset request
if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
  if ($user->initResetPassword($_POST['username'], $smarty)) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Please check your mail account to finish your password reset', 'TYPE' => 'alert alert-success');
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => htmlentities($user->getError(), ENT_QUOTES), 'TYPE' => 'alert alert-danger');
  }
} else {
  $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
}

// Tempalte specifics, user default template by parent page
$smarty->assign("CONTENT", "../default.tpl");
?>
