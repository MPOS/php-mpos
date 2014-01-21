<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($user->isAuthenticated()) {
  if ($config['twofactor']['enabled'] && $config['twofactor']['mode'] == 'gauth' && $config['twofactor']['options']['login']) {
    $smarty->assign("GAUTH_ENABLED", true);
    $email = $user->getUserEmail($_SESSION['USERDATA']['username']);
    $smarty->assign("GAUTH_URL", $GAuth->getQRCodeGoogleUrl($email, $user->getGAuthKey($email)));
  }
  $smarty->assign("CONTENT", "default.tpl");
}
?>
