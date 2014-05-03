<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($user->isAuthenticated()) {
  if (!$setting->getValue('disable_invitations')) {
    if ($invitation->getCountInvitations($_SESSION['USERDATA']['id']) >= $config['accounts']['invitations']['count']) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'You have exceeded the allowed invitations of ' . $config['accounts']['invitations']['count'], 'TYPE' => 'alert alert-danger');
    } else if (isset($_POST['do']) && $_POST['do'] == 'sendInvitation') {
      if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
        if ($invitation->sendInvitation($_SESSION['USERDATA']['id'], $_POST['data'])) {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Invitation sent', 'TYPE' => 'alert alert-success');
        } else {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to send invitation to recipient: ' . $invitation->getError(), 'TYPE' => 'alert alert-danger');
        }
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
      }
    }
    $aInvitations = $invitation->getInvitations($_SESSION['USERDATA']['id']);
    $smarty->assign('INVITATIONS', $aInvitations);
  } else {
    $aInvitations = array();
    $_SESSION['POPUP'][] = array('CONTENT' => 'Invitations are disabled', 'TYPE' => 'alert alert-danger');
  }
}
$smarty->assign('CONTENT', 'default.tpl');
?>
