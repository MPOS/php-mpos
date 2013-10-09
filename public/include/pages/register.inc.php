<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($setting->getValue('lock_registration') && $setting->getValue('disable_invitations')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Account registration is currently disabled. Please try again later.', 'TYPE' => 'errormsg');
  $smarty->assign("CONTENT", "disabled.tpl");
} else if ($setting->getValue('lock_registration') && !$setting->getValue('disable_invitations') && !isset($_GET['token'])) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Only invited users are allowed to register.', 'TYPE' => 'errormsg');
  $smarty->assign("CONTENT", "disabled.tpl");
} else {
  if ($setting->getValue('recaptcha_enabled')) {
    require_once(INCLUDE_DIR . '/lib/recaptchalib.php');
    $smarty->assign("RECAPTCHA", recaptcha_get_html($setting->getValue('recaptcha_public_key')));
  }
  $smarty->assign("CONTENT", "default.tpl");
}
?>
