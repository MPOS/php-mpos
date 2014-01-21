<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// GAuth if enabled
//$config['twofactor']['mode']  

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
    if ($user->checkLogin(@$_POST['username'], @$_POST['password']) ) {
      if ($config['twofactor']['enabled'] && $config['twofactor']['mode'] == 'gauth' && $config['twofactor']['options']['login']) {
        // first off, have they already got a key set?
        $key = $user->getGAuthKey($_POST['username']);
        //print_r($key);
        if (!$key || $key == "" || empty($key)) {
          // nope, they don't, let's set it
          $keySet = $GAuth->createSecret();
          $user->setGAuthKey($_POST['username'], $keySet);
          $gauthed = 1;
        } else {
          $code = $GAuth->getCode($key);
          $gauth_token = $GAuth->verifyCode($key, $code, 2);
          if ($gauth_token == $_POST['gatoken']) {
            $gauthed = 1;
          } else {
            $gauthed = 0;
          }
        }
        // check GAuth key for validity
        //echo "$keySet secret, $code code verified $gauthed";exit();
      }
      if ($config['twofactor']['mode'] == 'gauth' && isset($gauthed) && $gauthed && $config['twofactor']['options']['login'] || $config['twofactor']['mode'] == '' || !$config['twofactor']['options']['login']) {
        // if gauth is on and we're authed, or mode is blank, or login option is turned off
        empty($_POST['to']) ? $to = $_SERVER['SCRIPT_NAME'] : $to = $_POST['to'];
        $port = ($_SERVER["SERVER_PORT"] == "80" or $_SERVER["SERVER_PORT"] == "443") ? "" : (":".$_SERVER["SERVER_PORT"]);
        $location = @$_SERVER['HTTPS'] === true ? 'https://' . $_SERVER['SERVER_NAME'] . $port . $to : 'http://' . $_SERVER['SERVER_NAME'] . $port . $to;
        if (!headers_sent()) header('Location: ' . $location);
        exit('<meta http-equiv="refresh" content="0; url=' . htmlspecialchars($location) . '"/>');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => "Unable to login using your GAuth token, are you sure it's correct?", 'TYPE' => 'errormsg');
      }
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'info');
    }
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Invalid Captcha, please try again.', 'TYPE' => 'errormsg');
  }
}
// Load login template
$smarty->assign('CONTENT', 'default.tpl');

?>
