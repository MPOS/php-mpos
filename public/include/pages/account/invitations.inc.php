<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($user->isAuthenticated()) {
  if (!$setting->getValue('disable_invitations')) {
    // csrf stuff
    $csrfenabled = ($config['csrf']['enabled'] && $config['csrf']['options']['sitewide']) ? 1 : 0;
    if ($csrfenabled) {
      $nocsrf = ($csrftoken->getBasic($user->getCurrentIP(), 'invitations', 'mdyH') == @$_POST['ctoken']) ? 1 : 0;
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
        $img = $csrftoken->getDescriptionImageHTML();
        $_SESSION['POPUP'][] = array('CONTENT' => "Invitation token expired, please try again $img", 'TYPE' => 'info');
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
if ($csrfenabled) {
  $token = $csrftoken->getBasic($user->getCurrentIP(), 'invitations', 'mdyH');
  $smarty->assign('CTOKEN', $token);
}
$smarty->assign('CONTENT', 'default.tpl');
?>
