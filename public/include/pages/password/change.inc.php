<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

// csrf stuff
$csrfenabled = ($config['csrf']['enabled'] && $config['csrf']['options']['sitewide']) ? 1 : 0;
if ($csrfenabled) {
  // we have to use editaccount token because this can be called from 2 separate places
  $nocsrf = ($csrftoken->getBasic($user->getCurrentIP(), 'editaccount') == @$_POST['ctoken']) ? 1 : 0;
}

if (!$csrfenabled || $csrfenabled && $nocsrf) {
  if (isset($_POST['do']) && $_POST['do'] == 'resetPassword') {
    if ($user->resetPassword($_POST['token'], $_POST['newPassword'], $_POST['newPassword2'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Password reset complete! Please login.', 'TYPE' => 'success');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $user->getError(), 'TYPE' => 'errormsg');
    }
  }
} else {
  $img = $csrftoken->getDescriptionImageHTML();
  $_SESSION['POPUP'][] = array('CONTENT' => "Page token expired, please try again $img", 'TYPE' => 'info');
}

// csrf token
if ($config['csrf']['enabled'] && $config['csrf']['options']['sitewide']) {
  $token = $csrftoken->getBasic($user->getCurrentIP(), 'editaccount');
  $smarty->assign('CTOKEN', $token);
}

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");

?>
