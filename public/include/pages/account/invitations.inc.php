<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($user->isAuthenticated()) {
  if (!$setting->getValue('disable_invitations')) {
    if ($invitation->getCountInvitations($_SESSION['USERDATA']['id']) >= $config['accounts']['invitations']['count']) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'You have exceeded the allowed invitations of ' . $config['accounts']['invitations']['count'], 'TYPE' => 'errormsg');
    } else if (isset($_POST['do']) && $_POST['do'] == 'sendInvitation') {
      if ($invitation->sendInvitation($_SESSION['USERDATA']['id'], $_POST['data'])) {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Invitation sent');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to send invitation to recipient: ' . $invitation->getError(), 'TYPE' => 'errormsg');
      }
    }
    $aInvitations = $invitation->getInvitations($_SESSION['USERDATA']['id']);
    $smarty->assign('INVITATIONS', $aInvitations);
  } else {
    $aInvitations = array();
    $_SESSION['POPUP'][] = array('CONTENT' => 'Invitations are disabled', 'TYPE' => 'errormsg');
  }
}
$smarty->assign('CONTENT', 'default.tpl');
?>
