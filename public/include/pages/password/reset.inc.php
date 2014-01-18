<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// csrf stuff
$csrfenabled = ($config['csrf']['enabled'] && $config['csrf']['options']['sitewide']) ? 1 : 0;
if ($csrfenabled) {
  $nocsrf = ($csrftoken->getBasic($user->getCurrentIP(), 'resetaccount') == @$_POST['ctoken']) ? 1 : 0;
}

// Process password reset request
if (!$csrfenabled || $csrfenabled && $nocsrf) {
  if ($user->initResetPassword($_POST['username'], $smarty)) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Please check your mail account to finish your password reset', 'TYPE' => 'success');
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => htmlentities($user->getError(), ENT_QUOTES), 'TYPE' => 'errormsg');
  }
} else {
  $img = $csrftoken->getDescriptionImageHTML();
  $_SESSION['POPUP'][] = array('CONTENT' => "Page token expired, please try again $img", 'TYPE' => 'info');
}

// csrf token
if ($config['csrf']['enabled'] && $config['csrf']['options']['sitewide']) {
  $token = $csrftoken->getBasic($user->getCurrentIP(), 'resetaccount');
  $smarty->assign('CTOKEN', $token);
}
// Tempalte specifics, user default template by parent page
$smarty->assign("CONTENT", "../default.tpl");
?>
