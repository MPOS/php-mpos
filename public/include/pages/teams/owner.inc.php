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
    } else if (! $team->isFounder($team->memberOf($_SESSION['USERDATA']['id']), $_SESSION['USERDATA']['id'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'You are not the owner of this team', 'TYPE' => 'info');
    } else if (@$_REQUEST['do'] == 'change') {
      if ($team->changeOwner($_SESSION['USERDATA']['id'], $_REQUEST['team_id'], $_REQUEST['owner'])) {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Ownership changed to ' . $user->getUserName($_REQUEST['owner']));
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to change ownership: ' . $team->getError(), 'TYPE' => 'errormsg');
      }
    }
    $smarty->assign('TEAM_MEMBERS', $team->getMembers($team->memberOf($_SESSION['USERDATA']['id'])));
    $smarty->assign('CONTENT', 'default.tpl');
  } else { $smarty->assign('CONTENT', '../../global/empty.tpl'); }
}
?>
