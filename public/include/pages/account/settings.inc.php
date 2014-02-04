<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($user->isAuthenticated()) {
  if (@$_REQUEST['do'] == 'save' && !empty($_REQUEST['data']) && !$uSetting->getValue('lock_settings', @$_SESSION['USERDATA']['id'])) {
    foreach($_REQUEST['data'] as $var => $value) {
      // extra param is binary, none of these settings use a real string yet
      $uSetting->setValue($var, @$_SESSION['USERDATA']['id'], $value, true);
    }
    $_SESSION['POPUP'][] = array('CONTENT' => 'Settings updated', 'TYPE' => 'success');
  } else {
    require_once(INCLUDE_DIR . '/config/user_settings.inc.php');
    if (count(@$uSettings) > 0) {
      // Load our available settings from configuration
      $locked = $uSetting->getValue('lock_settings', @$_SESSION['USERDATA']['id']);
      $editable = $user->token->isTokenValid($_SESSION['USERDATA']['id'], @$_GET['us_token'], 9);
      $sent = $user->token->doesTokenExist('unlock_settings', $_SESSION['USERDATA']['id']);
      if ($sent && $editable) {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Your settings have been unlocked.', 'TYPE' => 'success');
        $delete = $user->token->deleteToken(@$_GET['us_token']);
        $uSetting->setValue('lock_settings', @$_SESSION['USERDATA']['id'], 0, true);
      } else {
        if (@$_REQUEST['do'] == 'save' && !empty($_REQUEST['data']) && $locked) {
          if ($sent && !$editable) {
            $_SESSION['POPUP'][] = array('CONTENT' => 'Check your e-mail to unlock your settings.', 'TYPE' => 'success');
          } else if (!$sent && !$editable && @$_REQUEST['data']['lock_settings'] == 0) {
            $user->sendChangeConfigEmail('unlock_settings', @$_SESSION['USERDATA']['id']);
            $_SESSION['POPUP'][] = array('CONTENT' => 'An e-mail was sent with a link to unlock your settings.', 'TYPE' => 'success');
          } else {
            $_SESSION['POPUP'][] = array('CONTENT' => 'Your settings are locked. Unlocking requires an e-mail confirmation.', 'TYPE' => 'success');
          }
        }
      }
      // Load onto the template
      $smarty->assign("SETTINGS", $uSettings);
      // Tempalte specifics
      $smarty->assign("CONTENT", "default.tpl");
    } else {
      $smarty->assign("CONTENT", "disabled.tpl");
    }
  }
}

?>
