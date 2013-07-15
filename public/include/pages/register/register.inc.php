<?php
// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($config['recaptcha']['enabled']) {
  // Load re-captcha specific data
  require_once(INCLUDE_DIR . '/lib/recaptchalib.php');
  $rsp = recaptcha_check_answer (
    $config['recaptcha']['private_key'],
    $_SERVER["REMOTE_ADDR"],
    $_POST["recaptcha_challenge_field"],
    $_POST["recaptcha_response_field"]
  );
}

if ($setting->getValue('disable_invitations') && $setting->getValue('lock_registration')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Account registration is currently disabled. Please try again later.', 'TYPE' => 'errormsg');
} else {
  // Check if recaptcha is enabled, process form data if valid
  if($config['recaptcha']['enabled'] && $_POST["recaptcha_response_field"] && $_POST["recaptcha_response_field"]!=''){
    if ($rsp->is_valid) {
      $smarty->assign("RECAPTCHA", recaptcha_get_html($config['recaptcha']['public_key']));
      if ($user->register($_POST['username'], $_POST['password1'], $_POST['password2'], $_POST['pin'], $_POST['email1'], $_POST['email2'], $_POST['token'])) {
        $config['accounts']['confirm_email']['enabled'] ? $_SESSION['POPUP'][] = array('CONTENT' => 'Please check your mailbox to activate this account') : $_SESSION['POPUP'][] = array('CONTENT' => 'Account created, please login');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to create account: ' . $user->getError(), 'TYPE' => 'errormsg');
      }
    } else {
      $smarty->assign("RECAPTCHA", recaptcha_get_html($config['recaptcha']['public_key'], $rsp->error));
      $_SESSION['POPUP'][] = array('CONTENT' => 'Invalid Captcha, please try again. (' . $rsp->error . ')', 'TYPE' => 'errormsg');
    }
    // Empty captcha
  } else if ($config['recaptcha']['enabled']) {
    $smarty->assign("RECAPTCHA", recaptcha_get_html($config['recaptcha']['public_key'], $rsp->error));
    $_SESSION['POPUP'][] = array('CONTENT' => 'Empty Captcha, please try again.', 'TYPE' => 'errormsg');
    // Captcha disabled
  } else {
    if ($user->register($_POST['username'], $_POST['password1'], $_POST['password2'], $_POST['pin'], $_POST['email1'], $_POST['email2'], $_POST['token'])) {
      $config['accounts']['confirm_email']['enabled'] ? $_SESSION['POPUP'][] = array('CONTENT' => 'Please check your mailbox to activate this account') : $_SESSION['POPUP'][] = array('CONTENT' => 'Account created, please login');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to create account: ' . $user->getError(), 'TYPE' => 'errormsg');
    }
  }
}

// We load the default registration template instead of an action specific one
$smarty->assign("CONTENT", "../default.tpl");
?>
