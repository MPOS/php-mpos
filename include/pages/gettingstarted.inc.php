<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

$smarty->assign("SITESTRATUMURL", $config['gettingstarted']['stratumurl']);
$smarty->assign("SITESTRATUMPORT", $config['gettingstarted']['stratumport']);
$smarty->assign("SITECOINNAME", $config['gettingstarted']['coinname']);
$smarty->assign("SITECOINURL", $config['gettingstarted']['coinurl']);

switch($setting->getValue('acl_show_help_loggedin', 1)) {
case '0':
  $smarty->assign("CONTENT", "default.tpl");
  break;
case '1':
  if ($user->isAuthenticated()) {
    $smarty->assign("CONTENT", "default.tpl");
  }
  break;
}