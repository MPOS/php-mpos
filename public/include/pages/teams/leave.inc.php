<?php
// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($setting->getValue('disable_teams') == 1) { 
  $_SESSION['POPUP'][] = array('CONTENT' => 'Teams disabled by admin.', 'TYPE' => 'info');
  $smarty->assign('CONTENT', '../../global/empty.tpl');
} else {
  if ($user->isAuthenticated()) {
    if (! $team->memberOf($_SESSION['USERDATA']['id'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'You are not part of any team', 'TYPE' => 'info');
    } else if (@$_REQUEST['do'] == 'leave') {
      if ($team->leave($_SESSION['USERDATA']['id'])) {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Team Left');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to leave your team:' . $team->getError(), 'TYPE' => 'errormsg');
      }
      $smarty->assign('CONTENT', 'default.tpl');
    } else {
      $smarty->assign('CONTENT', 'default.tpl');
    }
  } else { $smarty->assign('CONTENT', '../../global/empty.tpl'); }
}
?>
