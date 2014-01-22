<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// csrf if enabled
$csrfenabled = ($config['csrf']['enabled'] && !in_array('dgauth', $config['csrf']['disabled_forms'])) ? 1 : 0;
if ($csrfenabled) {
  $nocsrf = ($csrftoken->getBasic($user->getCurrentIP(), 'dgauth') == @$_POST['ctoken']) ? 1 : 0;
}

$oldtoken = (!isset($_GET['da_token']) || empty($_GET['da_token'])) ? @$_POST['da_token'] : @$_GET['da_token'];

// check the disable gauth token for validity
if ($config['twofactor']['options']['login'] && $config['twofactor']['mode'] == 'gauth' && $config['twofactor']['enabled']) {
  $userid = $oToken->getTokenCreator('disable_gauth', $oldtoken);
  $username = $user->getUserName($userid);
  $email = $user->getUserEmail($username);
  $da_editable = $user->token->isTokenValid($userid, $oldtoken, 8);
  $da_sent = $user->token->doesTokenExist('disable_gauth', $userid);
  $uses_gauth = $user->getUserGAuthEnabledByEmail($email);
}

if ($setting->getValue('maintenance') && !$user->isAdmin($user->getUserId($_POST['username']))) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'You are not allowed to login during maintenace.', 'TYPE' => 'info');
} else if (!empty($_POST['username']) && !empty($_POST['password'])) {
  $nocsrf = 1;
  if ($config['csrf']['enabled'] && !in_array('login', $config['csrf']['disabled_forms'])) {
    if ((isset($_POST['ctoken']) && $_POST['ctoken'] !== $csrftoken->getBasic($user->getCurrentIP(), 'login')) || (!isset($_POST['ctoken']))) {
      // csrf protection is on and this token is invalid, error out -> time expired
      $nocsrf = 0;
    }
  }
  if (($nocsrf == 1 || (!$config['csrf']['enabled'] || in_array('gauth', $config['csrf']['disabled_forms'])))) {
    if ($config['twofactor']['options']['login'] && $config['twofactor']['mode'] == 'gauth' && $config['twofactor']['enabled']) {
      $checkpin = $user->checkPin($user->getUserId($username), @$_POST['authPin']);
      $checklogin = $user->checkLogin(@$_POST['username'], @$_POST['password']);
      if ($checkpin) {
        if ($checklogin && $uses_gauth && $da_sent && $da_editable) {
          // everything is good, let's disable it and wipe the old secret just in case
          $userid = $oToken->getTokenCreator('disable_gauth', $oldtoken);
          $username = $user->getUserName($userid);
          $email = $user->getUserEmail($username);
          $set_gauth_disabled = $user->setUserGAuthEnabled($email, 0);
          $set_gauth_key_blank = $user->setGAuthKey($email, '');
          // push straight to gauth so they can see new settings
          $port = ($_SERVER["SERVER_PORT"] == "80" or $_SERVER["SERVER_PORT"] == "443") ? "" : (":".$_SERVER["SERVER_PORT"]);
          $location = @$_SERVER['HTTPS'] === true ? 'https://' . $_SERVER['SERVER_NAME'] . $port . $_SERVER['SCRIPT_NAME']. '?page=account&action=gauth' : 'http://' . $_SERVER['SERVER_NAME'] . $port . $_SERVER['SCRIPT_NAME'] . '?page=account&action=gauth';
          if (!headers_sent()) header('Location: ' . $location);
          exit('<meta http-equiv="refresh" content="0; url=' . htmlspecialchars($location) . '"/>');
        } else {
          if (!$checklogin) {
            $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to login: '. $user->getError(), 'TYPE' => 'errormsg');
          } else if ($uses_gauth !== 0) {
            $_SESSION['POPUP'][] = array('CONTENT' => "Google Authentication isn't enabled", 'TYPE' => 'errormsg');
          } else if ($da_sent && !$da_editable) {
            $_SESSION['POPUP'][] = array('CONTENT' => "Invalid confirmation token", 'TYPE' => 'errormsg');
          } else if (!$da_sent) {
            $_SESSION['POPUP'][] = array('CONTENT' => "A token doesn't exist to confirm this change", 'TYPE' => 'errormsg');
          }
        }
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Incorrect pin', 'TYPE' => 'errormsg');
      }
    } else {
      // gauth is not enabled
      $_SESSION['POPUP'][] = array('CONTENT' => "Google Authentication is disabled", 'TYPE' => 'errormsg');
    }
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'info');
  }
}
// csrf token
if ($csrfenabled && !in_array('dgauth', $config['csrf']['disabled_forms'])) {
  $token = $csrftoken->getBasic($user->getCurrentIP(), 'dgauth');
  $smarty->assign('CTOKEN', $token);
}
if ($user->isAuthenticated(false)) {
  $smarty->assign('CONTENT', 'disabled.tpl');
} else {
  // Load login template
  if ($config['twofactor']['options']['login'] && $config['twofactor']['mode'] == 'gauth' && $config['twofactor']['enabled']) {
    if ($da_sent && $da_editable) {
      // we only do this down here because otherwise it ends up showing again after successful login
      $_SESSION['POPUP'][] = array('CONTENT' => "Fill in your current details to continue", 'TYPE' => 'success');
    }
    $smarty->assign('CONTENT', 'default.tpl');
  }
}
?>
