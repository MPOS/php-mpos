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

if ($setting->getValue('disable_mailform')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Mailform is currently disabled. Please try again later.', 'TYPE' => 'errormsg');
} else {
  // Check if recaptcha is enabled, process form data if valid
  if($config['recaptcha']['enabled'] && $_POST["recaptcha_response_field"] && $_POST["recaptcha_response_field"]!=''){
    if ($rsp->is_valid) {
      $smarty->assign("RECAPTCHA", recaptcha_get_html($config['recaptcha']['public_key']));
      if ($user->mailform($_POST['senderName'], $_POST['senderEmail'], $_POST['senderSubject'], $_POST['senderMesage'])) {
        $config['mailform']['enabled'] ? $_SESSION['POPUP'][] = array('CONTENT' => 'Thanks for sending your message! We will get back to you shortly') : $_SESSION['POPUP'][] = array('CONTENT' => 'Message Sent');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => 'There was a problem sending your message. Please try again.' . $user->getError(), 'TYPE' => 'errormsg');
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
      if ($user->mailform($_POST['senderName'], $_POST['senderEmail'], $_POST['senderSubject'], $_POST['senderMessage'])) {
      $config['mailform']['enabled'] ? $_SESSION['POPUP'][] = array('CONTENT' => 'Thanks for sending your message! We will get back to you shortly') : $_SESSION['POPUP'][] = array('CONTENT' => 'Message Sent');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => 'There was a problem sending your message. Please try again. ' . $user->getError(), 'TYPE' => 'errormsg');
    }
  }
}

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");

?>
