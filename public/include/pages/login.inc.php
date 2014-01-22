<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

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
  } else {
    $smarty->assign("RECAPTCHA", recaptcha_get_html($setting->getValue('recaptcha_public_key'), null, true));
  }
}

if ($setting->getValue('maintenance') && !$user->isAdmin($user->getUserIdByEmail($_POST['username']))) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'You are not allowed to login during maintenace.', 'TYPE' => 'info');
} else if (!empty($_POST['username']) && !empty($_POST['password'])) {
  // Check if recaptcha is enabled, process form data if valid

  if (($setting->getValue('recaptcha_enabled') != 1 || $setting->getValue('recaptcha_enabled_logins') != 1 || $rsp->is_valid) && ($nocsrf == 1 || (!$config['csrf']['enabled'] || in_array('login', $config['csrf']['disabled_forms'])))) {
    if ($config['twofactor']['enabled'] && $config['twofactor']['mode'] == 'gauth' && $config['twofactor']['options']['login']) {
      // check GAuth token if we need to
      $uses_gauth = $user->getUserGAuthEnabledByEmail($_POST['username']);
      if ($uses_gauth > 0) {
        $gauthed = $user->isGAuthTokenValid($_POST['username'], @$_POST['gatoken']);
      }
    }
    if ($config['twofactor']['enabled'] && $config['twofactor']['mode'] == 'gauth' && $uses_gauth > 0 && $gauthed && $config['twofactor']['options']['login'] || $config['twofactor']['mode'] == '' || !$config['twofactor']['options']['login'] || !$config['twofactor']['enabled'] || $uses_gauth == 0) {
      // if gauth is correct or disabled continue
      if ($user->checkLogin(@$_POST['username'], @$_POST['password']) ) {
        if ($config['twofactor']['enabled'] && $config['twofactor']['mode'] == 'gauth' && $uses_gauth > 0 && $gauthed) {
          // if token isn't hidden already, we should hide it because this is the first successful login with it on
          if ($uses_gauth == 1) {
            $user->setUserGAuthEnabled($_POST['username'], 2);
          }
        }
        empty($_POST['to']) ? $to = $_SERVER['SCRIPT_NAME'] : $to = $_POST['to'];
        $port = ($_SERVER["SERVER_PORT"] == "80" or $_SERVER["SERVER_PORT"] == "443") ? "" : (":".$_SERVER["SERVER_PORT"]);
        $location = @$_SERVER['HTTPS'] === true ? 'https://' . $_SERVER['SERVER_NAME'] . $port . $to : 'http://' . $_SERVER['SERVER_NAME'] . $port . $to;
        if (!headers_sent()) header('Location: ' . $location);
        exit('<meta http-equiv="refresh" content="0; url=' . htmlspecialchars($location) . '"/>');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to login: '. $user->getError(), 'TYPE' => 'errormsg');
      }
    } else {
      // gauth is enabled & incorrect, inc failed logins/display error
      if ($config['twofactor']['mode'] == 'gauth' && $uses_gauth > 0 && !$gauthed) {
        $user->tokenFailedGAuth($_POST['username']);
        $_SESSION['POPUP'][] = array('CONTENT' => "Unable to login: ".$user->getError(), 'TYPE' => 'errormsg');
      }
    }
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Invalid Captcha, please try again.', 'TYPE' => 'errormsg');
  }
}
// Load login template
$smarty->assign('CONTENT', 'default.tpl');

?>
