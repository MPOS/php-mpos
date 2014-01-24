<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
  if (isset($_POST['do']) && $_POST['do'] == 'resetPassword') {
    if ($user->resetPassword($_POST['token'], $_POST['newPassword'], $_POST['newPassword2'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Password reset complete! Please login.', 'TYPE' => 'success');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $user->getError(), 'TYPE' => 'errormsg');
    }
  }
} else {
  $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'info');
}

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");

?>