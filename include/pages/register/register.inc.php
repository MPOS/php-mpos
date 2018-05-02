<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

$recaptcha_enabled = ($setting->getValue('recaptcha_enabled') && $setting->getValue('recaptcha_enabled_registrations'));

$smarty->assign("recaptcha_enabled", $recaptcha_enabled);

// ReCaptcha handling if enabled
if ($recaptcha_enabled) {
  $recaptcha_secret = $setting->getValue('recaptcha_private_key');
  $recaptcha_public_key = $setting->getValue('recaptcha_public_key');

  $smarty->assign("recaptcha_public_key", $recaptcha_public_key);

  $recaptcha = new \ReCaptcha\ReCaptcha($recaptcha_secret);

  // Load re-captcha specific data
  $recaptcha_response = (isset($_POST["g-recaptcha-response"]) ? $_POST["g-recaptcha-response"] : null);
  $rsp = $recaptcha->verify($recaptcha_response, $_SERVER["REMOTE_ADDRESS"]);

  if (!$rsp->isSuccess()) $_SESSION['POPUP'][] = array('CONTENT' => 'Invalid Captcha, please try again.', 'TYPE' => 'alert alert-danger');
}

if ($setting->getValue('disable_invitations') && $setting->getValue('lock_registration')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Account registration is currently disabled. Please try again later.', 'TYPE' => 'alert alert-danger');
} else if ($setting->getValue('lock_registration') && !$setting->getValue('disable_invitations') && !isset($_POST['token'])) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Only invited users are allowed to register.', 'TYPE' => 'alert alert-danger');
} else {
  // Check if csrf is enabled and fail if token is invalid
  if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
    if (($recaptcha_enabled && $rsp->isSuccess()) || !$recaptcha_enabled) {
      // Check if recaptcha is enabled, process form data if valid or disabled
      isset($_POST['token']) ? $token = $_POST['token'] : $token = '';
      isset($_POST['coinaddress']) ? $validcoinaddress = $_POST['coinaddress'] : $validcoinaddress = NULL;
      if ($config['check_valid_coinaddress'] AND empty($validcoinaddress)) {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Please enter a valid Wallet Address', 'TYPE' => 'alert alert-danger');
      } else {
        if ($user->register(@$_POST['username'], $validcoinaddress, @$_POST['password1'], @$_POST['password2'], @$_POST['pin'], @$_POST['email1'], @$_POST['email2'], @$_POST['tac'], $token)) {
          (!$setting->getValue('accounts_confirm_email_disabled')) ? $_SESSION['POPUP'][] = array('CONTENT' => 'Please check your mailbox to activate this account') : $_SESSION['POPUP'][] = array('CONTENT' => 'Account created, please login');
        } else {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to create account: ' . $user->getError(), 'TYPE' => 'alert alert-danger');
        }
      }
    }
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
  }
}

// We load the default registration template instead of an action specific one
$smarty->assign("CONTENT", "../default.tpl");
