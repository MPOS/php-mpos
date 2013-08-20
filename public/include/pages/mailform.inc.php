<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($setting->getValue('disable_mailform')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Mailform is currently disabled. Please try again later.', 'TYPE' => 'errormsg');
  $smarty->assign("CONTENT", "disabled.tpl");
} else {
  if ($user->isAuthenticated()) {
  }
  if ($config['recaptcha']['enabled']) {
    require_once(INCLUDE_DIR . '/lib/recaptchalib.php');
    $smarty->assign("RECAPTCHA", recaptcha_get_html($config['recaptcha']['public_key']));
  }
  // Tempalte specifics
  $smarty->assign("CONTENT", "default.tpl");
}
?>
