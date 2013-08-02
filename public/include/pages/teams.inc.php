<?php
// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($setting->getValue('disable_teams') == 1) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Teams disabled by admin.', 'TYPE' => 'info');
  $smarty->assign('CONTENT', '../global/empty.tpl');
} else {
  if ($user->isAuthenticated()) {
    if ($team_id = $team->memberOf($_SESSION['USERDATA']['id']))
      $smarty->assign('TEAM_NAME', $team->getName($team_id));
    if ($team->isFounder($team_id, $_SESSION['USERDATA']['id']))
      $smarty->assign('TEAM_MEMBERS', $team->getMembers($team_id));

    $smarty->assign('CONTENT', 'default.tpl');
  } else { $smarty->assign('CONTENT', '../global/empty.tpl'); }
}
?>
