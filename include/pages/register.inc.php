<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($setting->getValue('lock_registration') && $setting->getValue('disable_invitations')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Account registration is currently disabled. Please try again later.', 'TYPE' => 'alert alert-danger');
  $smarty->assign("CONTENT", "disabled.tpl");
} else if ($setting->getValue('lock_registration') && !$setting->getValue('disable_invitations') && !isset($_GET['token'])) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Only invited users are allowed to register.', 'TYPE' => 'alert alert-danger');
  $smarty->assign("CONTENT", "disabled.tpl");
} else {
  $recaptcha_enabled = ($setting->getValue('recaptcha_enabled') && $setting->getValue('recaptcha_enabled_registrations'));
  $smarty->assign("recaptcha_enabled", $recaptcha_enabled);

  if ($recaptcha_enabled) {
    $recaptcha_public_key = $setting->getValue('recaptcha_public_key');
    $smarty->assign("recaptcha_public_key", $recaptcha_public_key);
  }

  // Load news entries for Desktop site and unauthenticated users
  $smarty->assign("CONTENT", "default.tpl");
}
