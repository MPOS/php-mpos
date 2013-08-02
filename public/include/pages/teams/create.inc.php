<?php
// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($setting->getValue('disable_teams') == 1) { 
  $_SESSION['POPUP'][] = array('CONTENT' => 'Teams disabled by admin.', 'TYPE' => 'info');
  $smarty->assign('CONTENT', '../../global/empty.tpl');
} else {
  if ($user->isAuthenticated()) {
    if (@$_REQUEST['do'] == 'create') {
      if ($team->create($_REQUEST['team']['name'], $_REQUEST['team']['slogan'], $_SESSION['USERDATA']['id'])) {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Team Created');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to create your team: ' . $team->getError(), 'TYPE' => 'errormsg');
      }
    }
    $smarty->assign('CONTENT', 'default.tpl');
  } else { $smarty->assign('CONTENT', '../../global/empty.tpl'); }
}
?>
