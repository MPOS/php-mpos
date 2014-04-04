<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($user->isAuthenticated()) {
  if ($setting->getValue('disable_notifications') == 1) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Notification system disabled by admin.', 'TYPE' => 'alert alert-warning');
    $smarty->assign('CONTENT', 'empty');
  } else {
    if (@$_REQUEST['do'] == 'save') {
      if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
        if ($notification->updateSettings($_SESSION['USERDATA']['id'], $_REQUEST['data'])) {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Updated notification settings', 'TYPE' => 'alert alert-success');
        } else {
          $_SESSION['POPUP'][] = array('CONTENT' => $notification->getError(), 'TYPE' => 'alert alert-danger');
        }
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
      }
    }

    // Fetch notifications
    $aNotifications = $notification->getNotifications($_SESSION['USERDATA']['id']);
    if (!$aNotifications) $_SESSION['POPUP'][] = array('CONTENT' => 'Could not find any notifications', 'TYPE' => 'alert alert-danger');

    // Fetch global settings
    $smarty->assign('DISABLE_BLOCKNOTIFICATIONS', $setting->getValue('notifications_disable_block'));
    $smarty->assign('DISABLE_IDLEWORKERNOTIFICATIONS', $setting->getValue('notifications_disable_idle_worker'));
    $smarty->assign('DISABLE_POOLNEWSLETTER', $setting->getValue('notifications_disable_pool_newsletter'));

    // Fetch user notification settings
    $aSettings = $notification->getNotificationSettings($_SESSION['USERDATA']['id']);

    $smarty->assign('NOTIFICATIONS', $aNotifications);
    $smarty->assign('SETTINGS', $aSettings);
    $smarty->assign('CONTENT', 'default.tpl');
  }
}

?>
