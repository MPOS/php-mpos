<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($user->isAuthenticated()) {
  // csrf stuff
  $csrfenabled = ($config['csrf']['enabled'] && !in_array('gauth', $config['csrf']['disabled_forms'])) ? 1 : 0;
  if ($csrfenabled) {
    $nocsrf = ($csrftoken->getBasic($user->getCurrentIP(), 'gauth') == @$_POST['ctoken']) ? 1 : 0;
  }
  
  $setting_gauth = (isset($_POST['user_gauth']) && $_POST['user_gauth'] == 1) ? (int)$_POST['user_gauth'] : 0;
  $email = $user->getUserEmail($_SESSION['USERDATA']['username']);
  $current_gauth = $user->getUserGAuthEnabledByEmail($email);
  
  if (isset($_POST['hide_secret'])) {
    if ($current_gauth == 1) {
      // well, they asked for it...
      $email = $user->getUserEmail($_SESSION['USERDATA']['username']);
      $user->setUserGAuthEnabled($email, 2);
    }
  }
  
  if (isset($_POST['reset_secret']) || isset($_POST['update_gauth']) && @$_POST['user_gauth'] == 0 && $current_gauth > 0) {
    // reset/log out or attempted disable
    $send_token_reset = $user->sendChangeConfigEmail('disable_gauth', $_SESSION['USERDATA']['id']);
    if ($send_token_reset) {
      $user->logoutUser("", "?page=account&action=disablegauth");
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => "Failed to send the notification: ".$user->getError(), 'TYPE' => 'errormsg');
    }
  }
  
  if (isset($_POST['user_gauth']) && isset($_POST['update_gauth'])) {
    $email = $user->getUserEmail($_SESSION['USERDATA']['username']);
    $current_gauth = $user->getUserGAuthEnabledByEmail($email);
    
    if ($current_gauth > 0 && $setting_gauth == 0) {
      // disable catchall
      $send_token_disable = $user->sendChangeConfigEmail('disable_gauth', $_SESSION['USERDATA']['id']);
      // and log out
      if ($send_token_disable) {
        $user->logoutUser($_SERVER['REQUEST_URI']);
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => "Failed to send the notification: ".$user->getError(), 'TYPE' => 'errormsg');
      }
    }
    
    if ($current_gauth == 0) {
      // hasn't been enabled before, generate their key
      $set_gauth_key = $GAuth->createSecret();
      $gauth_key_exists = $user->getGAuthKeyExists($set_gauth_key);
      // this is stupid but we should be doing this - if key exists keep creating until we get one that doesn't
      if ($gauth_key_exists > 0) {
        // due to some weird php 5.4 -> 5.5 differences idk how to loop this a sane way, so look out, getto recheck ahoy
        $set_gauth_key = $GAuth->createSecret();
        $gauth_key_exists = $user->getGAuthKeyExists($set_gauth_key);
        if ($gauth_key_exists > 0) {
          $set_gauth_key = $GAuth->createSecret();
          $gauth_key_exists = $user->getGAuthKeyExists($set_gauth_key);
          if ($gauth_key_exists > 0) {
            $set_gauth_key = $GAuth->createSecret();
            $gauth_key_exists = $user->getGAuthKeyExists($set_gauth_key);
            if ($gauth_key_exists > 0) {
              // welp, 3rd times a charm, let's fail it
              $_SESSION['POPUP'][] = array('CONTENT' => "Creation of a valid Google Authentication key failed, please contact the administrator.", 'TYPE' => 'errormsg');
            }
          }
        }
      }
      if ($gauth_key_exists == 0) {
        $user->setGAuthKey($email, $set_gauth_key);
      }
    }
    $user->setUserGAuthEnabled($email, $setting_gauth);
  }
  
  if ($config['twofactor']['enabled'] && $config['twofactor']['mode'] == 'gauth' && $config['twofactor']['options']['login']) {
    $smarty->assign("GAUTH_ENABLED", true);
    $email = $user->getUserEmail($_SESSION['USERDATA']['username']);
    $user_enabled = $user->getUserGAuthEnabledByEmail($email);
    $user_key = $user->getGAuthKey($email);
    $gauth_url = ($user_enabled > 0) ? $GAuth->getQRCodeGoogleUrl($email, $user_key) : '';
    $gauth_key = ($user_enabled > 0) ? $user_key : '';
    $smarty->assign("USER_GAUTH", $user_enabled);
    $smarty->assign("GAUTH_URL", $gauth_url);
    $smarty->assign("GAUTH_KEY", $user->getGAuthKey($email));
  }
  
  if ($csrfenabled && !in_array('gauth', $config['csrf']['disabled_forms'])) {
    $token = $csrftoken->getBasic($user->getCurrentIP(), 'gauth');
    $smarty->assign('CTOKEN', $token);
  }
  $smarty->assign("CONTENT", "default.tpl");
}
?>