<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// ACL check
switch($setting->getValue('acl_moot_forum', 2)) {
case '0':
  if ($user->isAuthenticated()) {
    $smarty->assign('CHATROOM', $setting->getValue('system_moot_forum', 'lazypoolop'));
    $smarty->assign("CONTENT", "default.tpl");
  }
  break;
case '1':
  $smarty->assign('CHATROOM', $setting->getValue('system_moot_forum', 'lazypoolop'));
  $smarty->assign("CONTENT", "default.tpl");
  break;
case '2':
  $_SESSION['POPUP'][] = array('CONTENT' => 'Page currently disabled. Please try again later.', 'TYPE' => 'alert alert-danger');
  $smarty->assign("CONTENT", "disabled.tpl");
  break;
}
