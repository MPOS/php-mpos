<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($setting->getValue('acl_contactform') == 2) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Contactform is currently disabled. Please try again later.', 'TYPE' => 'alert alert-danger');
  $smarty->assign("CONTENT", "empty");
} else if ($setting->getValue('acl_contactform') == 0 && !$user->isAuthenticated(false)) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Contactform is disabled for guests.', 'TYPE' => 'alert alert-danger');
  $smarty->assign("CONTENT", "disabled.tpl");
} else {
  if ($setting->getValue('recaptcha_enabled') && $setting->getValue('recaptcha_enabled_contactform')) {
    require_once(INCLUDE_DIR . '/lib/recaptchalib.php');
    $smarty->assign("RECAPTCHA", recaptcha_get_html($setting->getValue('recaptcha_public_key')));
  }
  // Tempalte specifics
  $smarty->assign("CONTENT", "default.tpl");
}
?>
