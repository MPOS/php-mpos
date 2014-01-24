<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($user->isAuthenticated()) {
  if (!$setting->getValue('disable_invitations')) {
    // csrf stuff
    $csrfenabled = ($config['csrf']['enabled'] && !in_array('invitations', $config['csrf']['disabled_forms'])) ? 1 : 0;
    if ($csrfenabled) {
      $nocsrf = ($csrftoken->checkBasic($user->getCurrentIP(), 'invitations', @$_POST['ctoken'])) ? 1 : 0;
    }
    if ($invitation->getCountInvitations($_SESSION['USERDATA']['id']) >= $config['accounts']['invitations']['count']) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'You have exceeded the allowed invitations of ' . $config['accounts']['invitations']['count'], 'TYPE' => 'errormsg');
    } else if (isset($_POST['do']) && $_POST['do'] == 'sendInvitation') {
      if (!$csrfenabled || $csrfenabled && $nocsrf) {
        if ($invitation->sendInvitation($_SESSION['USERDATA']['id'], $_POST['data'])) {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Invitation sent', 'TYPE' => 'success');
        } else {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to send invitation to recipient: ' . $invitation->getError(), 'TYPE' => 'errormsg');
        }
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'info');
      }
    }
    $aInvitations = $invitation->getInvitations($_SESSION['USERDATA']['id']);
    $smarty->assign('INVITATIONS', $aInvitations);
  } else {
    $aInvitations = array();
    $_SESSION['POPUP'][] = array('CONTENT' => 'Invitations are disabled', 'TYPE' => 'errormsg');
  }
}
// csrf token
if ($csrfenabled && !in_array('invitations', $config['csrf']['disabled_forms'])) {
  $token = $csrftoken->getBasic($user->getCurrentIP(), 'invitations');
  $smarty->assign('CTOKEN', $token);
}
$smarty->assign('CONTENT', 'default.tpl');
?>
