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
  if (!$setting->getValue('recaptcha_enabled') || !$setting->getValue('recaptcha_enabled_logins') || ($setting->getValue('recaptcha_enabled') && $setting->getValue('recaptcha_enabled_logins') && $rsp->is_valid)) {
    if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
      // check if login is correct
      if ($user->checkLogin(@$_POST['username'], @$_POST['password']) ) {
        $port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : (":".$_SERVER["SERVER_PORT"]);
        $location = (@$_SERVER['HTTPS'] == "on") ? 'https://' : 'http://';
        $location .= $_SERVER['SERVER_NAME'] . $port . $_SERVER['SCRIPT_NAME'];
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
// Load login template
$smarty->assign('CONTENT', 'default.tpl');

?>
