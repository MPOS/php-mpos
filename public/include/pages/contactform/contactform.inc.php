<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// ReCaptcha handling if enabled
if ($setting->getValue('recaptcha_enabled') && $setting->getValue('recaptcha_enabled_contactform') && $setting->getValue('acl_contactform') != 2) {
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
}

if ($setting->getValue('acl_contactform') == 2) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Contactform is currently disabled. Please try again later.', 'TYPE' => 'alert alert-danger');
} else if ($setting->getValue('acl_contactform') == 0 && !$user->isAuthenticated(false)) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Contactform is disabled for guests.', 'TYPE' => 'alert alert-danger');
} else {
  // Check if recaptcha is enabled, process form data if valid
  if ($setting->getValue('recaptcha_enabled') != 1 || $setting->getValue('recaptcha_enabled_contactform') != 1 || $rsp->is_valid) {
    if ($mail->contactform($_POST['senderName'], $_POST['senderEmail'], $_POST['senderSubject'], $_POST['senderMessage'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Thanks for sending your message! We will get back to you shortly');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => 'There was a problem sending your message. Please try again. ' . $user->getError(), 'TYPE' => 'alert alert-danger');
    }
  }
}

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");

?>
