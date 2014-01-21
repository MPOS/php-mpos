<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// csrf if enabled
$csrfenabled = ($config['csrf']['enabled'] && !in_array('login', $config['csrf']['disabled_forms'])) ? 1 : 0;
if ($csrfenabled) {
  $nocsrf = ($csrftoken->getBasic($user->getCurrentIP(), 'login') == @$_POST['ctoken']) ? 1 : 0;
}

// ReCaptcha handling if enabled
if ($setting->getValue('recaptcha_enabled') && $setting->getValue('recaptcha_enabled_logins')) {
  require_once(INCLUDE_DIR . '/lib/recaptchalib.php');
  if (!empty($_POST['username']) && !empty($_POST['password'])) {
    // Load re-captcha specific data
    $rsp = recaptcha_check_answer (
      $setting->getValue('recaptcha_private_key'),
      $_SERVER["REMOTE_ADDR"],
      ( (isset($_POST["recaptcha_challenge_field"])) ? $_POST["recaptcha_challenge_field"] : null ),
      ( (isset($_POST["recaptcha_response_field"])) ? $_POST["recaptcha_response_field"] : null )
    );
    $smarty->assign("RECAPTCHA", recaptcha_get_html($setting->getValue('recaptcha_public_key'), $rsp->error, true));
    if (!$rsp->is_valid) $_SESSION['POPUP'][] = array('CONTENT' => 'Invalid Captcha, please try again.', 'TYPE' => 'errormsg');
  } else {
    $smarty->assign("RECAPTCHA", recaptcha_get_html($setting->getValue('recaptcha_public_key'), null, true));
  }
}

if ($setting->getValue('maintenance') && !$user->isAdmin($user->getUserId($_POST['username']))) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'You are not allowed to login during maintenace.', 'TYPE' => 'info');
} else if (!empty($_POST['username']) && !empty($_POST['password'])) {
  $nocsrf = 1;
  $recaptchavalid = 0;
  if ($setting->getValue('recaptcha_enabled') && $setting->getValue('recaptcha_enabled_logins') && $rsp->is_valid) {
    if ($rsp->is_valid) {
      // recaptcha is enabled and valid
      $recaptchavalid = 1;
    } else {
      // error out, invalid captcha
      $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to login: The captcha you entered was incorrect', 'TYPE' => 'errormsg');
    }
  }
  if ($config['csrf']['enabled'] && !in_array('login', $config['csrf']['disabled_forms'])) {
    if ((isset($_POST['ctoken']) && $_POST['ctoken'] !== $csrftoken->getBasic($user->getCurrentIP(), 'login')) || (!isset($_POST['ctoken']))) {
      // csrf protection is on and this token is invalid, error out -> time expired
      $nocsrf = 0;
    }
  }
  // Check if recaptcha is enabled, process form data if valid
  if (($setting->getValue('recaptcha_enabled') != 1 || $setting->getValue('recaptcha_enabled_logins') != 1 || $rsp->is_valid) && ($nocsrf == 1 || (!$config['csrf']['enabled'] || in_array('login', $config['csrf']['disabled_forms'])))) {
    if ($user->checkLogin(@$_POST['username'], @$_POST['password']) ) {
      empty($_POST['to']) ? $to = $_SERVER['SCRIPT_NAME'] : $to = $_POST['to'];
      $port = ($_SERVER["SERVER_PORT"] == "80" or $_SERVER["SERVER_PORT"] == "443") ? "" : (":".$_SERVER["SERVER_PORT"]);
      $location = @$_SERVER['HTTPS'] === true ? 'https://' . $_SERVER['SERVER_NAME'] . $port . $to : 'http://' . $_SERVER['SERVER_NAME'] . $port . $to;
      if (!headers_sent()) header('Location: ' . $location);
      exit('<meta http-equiv="refresh" content="0; url=' . htmlspecialchars($location) . '"/>');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to login: '. $user->getError(), 'TYPE' => 'errormsg');
    }
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'info');
  }
}
// csrf token
if ($csrfenabled && !in_array('login', $config['csrf']['disabled_forms'])) {
  $token = $csrftoken->getBasic($user->getCurrentIP(), 'login');
  $smarty->assign('CTOKEN', $token);
}
// Load login template
$smarty->assign('CONTENT', 'default.tpl');
?>
