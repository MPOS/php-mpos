<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

// Include markdown library
use \Michelf\Markdown;

if ($setting->getValue('notifications_disable_pool_newsletter', 0) == 1) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Pool newsletters are disabled.', 'TYPE' => 'alert alert-info');
  $smarty->assign("CONTENT", "");
} else {
  if (@$_REQUEST['do'] == 'send') {
    if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
      $iFailed = 0;
      $iSuccess = 0;
      foreach ($user->getAllAssoc() as $aData) {
        $aUserNotificationSettings = $notification->getNotificationSettings($aData['id']);
        if ($aData['is_locked'] != 0 || $aUserNotificationSettings['newsletter'] != 1) continue;
        $aData['subject'] = $_REQUEST['data']['subject'];
        $aData['CONTENT'] = $_REQUEST['data']['content'];
        if (!$mail->sendMail('newsletter/body', $aData, true)) {
          $iFailed++;
        } else {
          $iSuccess++;
        }
      }
      $_SESSION['POPUP'][] = array('CONTENT' => 'Newsletter sent to ' . $iSuccess . ' users.', 'TYPE' => 'alert alert-success');
      if ($iFailed > 0)
        $_SESSION['POPUP'][] = array('CONTENT' => 'Failed to send e-mail to ' . $iFailed . ' users. ', 'TYPE' => 'alert alert-info');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
    }
  }
  $smarty->assign("CONTENT", "default.tpl");
}
?>
