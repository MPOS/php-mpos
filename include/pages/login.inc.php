<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

$recaptcha_enabled = ($setting->getValue('recaptcha_enabled') && $setting->getValue('recaptcha_enabled_logins'));

if ($recaptcha_enabled) {
  $recaptcha_secret = $setting->getValue('recaptcha_private_key');
  $recaptcha_public_key = $setting->getValue('recaptcha_public_key');

  $recaptcha = new \ReCaptcha\ReCaptcha($recaptcha_secret);
  $smarty->assign("recaptcha_public_key", $recaptcha_public_key);
}

$smarty->assign("recaptcha_enabled", $recaptcha_enabled);

// ReCaptcha handling if enabled
if ($recaptcha_enabled) {
  if (!empty($_POST['username']) && !empty($_POST['password'])) {
    // Load re-captcha specific data

    $recaptcha_response = (isset($_POST["g-recaptcha-response"]) ? $_POST["g-recaptcha-response"] : null);
    $rsp = $recaptcha->verify($recaptcha_response, $_SERVER["REMOTE_ADDRESS"]);
  }
}

if (!empty($_POST['username']) && !empty($_POST['password'])) {
  if ($setting->getValue('maintenance') && !$user->isAdmin($user->getUserIdByEmail($_POST['username']))) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'You are not allowed to login during maintenace.', 'TYPE' => 'alert alert-info');
  } else {
    // Check if recaptcha is enabled, process form data if valid
    if (($recaptcha_enabled && $rsp->isSuccess()) || !$recaptcha_enabled) {
      if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
        // check if login is correct
        if ($user->checkLogin(@$_POST['username'], @$_POST['password']) ) {
          $port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : (":".$_SERVER["SERVER_PORT"]);
          $location = (@$_SERVER['HTTPS'] == "on") ? 'https://' : 'http://';
          $location .= $_SERVER['HTTP_HOST'] . $port . $_SERVER['SCRIPT_NAME'];
          $location.= '?page=dashboard';
          if (!headers_sent()) header('Location: ' . $location);
          exit('<meta http-equiv="refresh" content="0; url=' . htmlspecialchars($location) . '"/>');
        } else {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to login: '.$user->getError(), 'TYPE' => 'alert alert-danger');
        }
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
      }
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Invalid Captcha, please try again.', 'TYPE' => 'alert alert-danger');
    }
  }
}
// Load login template
$smarty->assign('CONTENT', 'default.tpl');
