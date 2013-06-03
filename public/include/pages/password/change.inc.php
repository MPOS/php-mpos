<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

if ($_POST['do'] == 'useToken') {
  if ($user->useToken($_POST['token'], $_POST['newPassword'], $_POST['newPassword2'])) {
     $_SESSION['POPUP'][] = array('CONTENT' => 'Password reset complete! Please login.');
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => $user->getError(), 'TYPE' => 'errormsg');
  }
}

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
