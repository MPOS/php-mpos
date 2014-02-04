<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

$oldtoken = (!isset($_GET['da_token']) || empty($_GET['da_token'])) ? @$_POST['da_token'] : @$_GET['da_token'];

// check the disable gauth token for validity
if ($config['twofactor']['mode'] == 'gauth' && $config['twofactor']['enabled']) {
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
  if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
    if ($config['twofactor']['mode'] == 'gauth' && $config['twofactor']['enabled']) {
      $checkpin = $user->checkPin($user->getUserId($username), @$_POST['authPin']);
      $checklogin = $user->checkLogin(@$_POST['username'], @$_POST['password']);
      if ($checkpin) {
        if ($checklogin && $uses_gauth && $da_sent && $da_editable && $checkpin) {
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
if ($user->isAuthenticated(false)) {
  $smarty->assign('CONTENT', 'disabled.tpl');
} else {
  // Load login template
  if ($config['twofactor']['mode'] == 'gauth' && $config['twofactor']['enabled']) {
    if ($da_sent && $da_editable) {
      // we only do this down here because otherwise it ends up showing again after successful login
      $_SESSION['POPUP'][] = array('CONTENT' => "Fill in your current details to continue", 'TYPE' => 'success');
    }
    $smarty->assign('CONTENT', 'default.tpl');
  }
}
?>
