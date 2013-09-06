<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($setting->getValue('recaptcha_enabled')) {
  require_once(INCLUDE_DIR . '/lib/recaptchalib.php');
  $smarty->assign("RECAPTCHA", recaptcha_get_html($setting->getValue('recaptcha_public_key')));

  // Tempalte specifics
 $smarty->assign("CONTENT", "default.tpl");
}
?>


