<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if (!$config['website']['registration']) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Account registration is currently disabled. Please try again later.', 'TYPE' => 'errormsg');
  $smarty->assign("CONTENT", "disabled.tpl");
} else {
  if ($config['recaptcha']['enabled']) {
    require_once(INCLUDE_DIR . '/lib/recaptchalib.php');
    $smarty->assign("RECAPTCHA", recaptcha_get_html($config['recaptcha']['public_key']));
  }
  // Tempalte specifics
  $smarty->assign("CONTENT", "default.tpl");
}
?>
