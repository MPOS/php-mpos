<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// ReCaptcha handling if enabled
if ($setting->getValue('recaptcha_enabled') && $setting->getValue('recaptcha_enabled_registrations')) {
  require_once(INCLUDE_DIR . '/lib/recaptchalib.php');
  // Load re-captcha specific data
  $rsp = recaptcha_check_answer (
    $setting->getValue('recaptcha_private_key'),
    $_SERVER["REMOTE_ADDR"],
    ( (isset($_POST["recaptcha_challenge_field"])) ? $_POST["recaptcha_challenge_field"] : null ),
    ( (isset($_POST["recaptcha_response_field"])) ? $_POST["recaptcha_response_field"] : null )
  );
  $smarty->assign("RECAPTCHA", recaptcha_get_html($setting->getValue('recaptcha_public_key'), $rsp->error, true));
  if (!$rsp->is_valid) $_SESSION['POPUP'][] = array('CONTENT' => 'Invalid Captcha, please try again.', 'TYPE' => 'alert alert-danger');
  $recaptcha = ($rsp->is_valid) ? 1 : 0;
}

if ($setting->getValue('disable_invitations') && $setting->getValue('lock_registration')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Account registration is currently disabled. Please try again later.', 'TYPE' => 'alert alert-danger');
} else if ($setting->getValue('lock_registration') && !$setting->getValue('disable_invitations') && !isset($_POST['token'])) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Only invited users are allowed to register.', 'TYPE' => 'alert alert-danger');
} else {
  // Check if csrf is enabled and fail if token is invalid
  if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
    if ($setting->getValue('recaptcha_enabled') != 1 || $setting->getValue('recaptcha_enabled_registrations') != 1 || $rsp->is_valid) {
      // Check if recaptcha is enabled, process form data if valid or disabled
      isset($_POST['token']) ? $token = $_POST['token'] : $token = '';
      if ($user->register(@$_POST['username'], @$_POST['coinaddress'], @$_POST['password1'], @$_POST['password2'], @$_POST['pin'], @$_POST['email1'], @$_POST['email2'], @$_POST['tac'], $token)) {
        (!$setting->getValue('accounts_confirm_email_disabled')) ? $_SESSION['POPUP'][] = array('CONTENT' => 'Please check your mailbox to activate this account') : $_SESSION['POPUP'][] = array('CONTENT' => 'Account created, please login');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to create account: ' . $user->getError(), 'TYPE' => 'alert alert-danger');
      }
    }
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
  }
}

// We load the default registration template instead of an action specific one
$smarty->assign("CONTENT", "../default.tpl");

?>
