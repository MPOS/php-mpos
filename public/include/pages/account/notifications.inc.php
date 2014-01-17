<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');
if ($user->isAuthenticated()) {
  if ($setting->getValue('disable_notifications') == 1) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Notification system disabled by admin.', 'TYPE' => 'info');
    $smarty->assign('CONTENT', 'empty');
  } else {
    // csrf stuff
    $csrfenabled = ($config['csrf']['enabled'] && $config['csrf']['options']['sitewide']) ? 1 : 0;
    if ($csrfenabled) {
      $nocsrf = ($csrftoken->getBasic($user->getCurrentIP(), 'editnotifs', 'mdyH') == @$_POST['ctoken']) ? 1 : 0;
    }
    
    if (@$_REQUEST['do'] == 'save') {
      if (!$csrfenabled || $csrfenabled && $nocsrf) {
        if ($notification->updateSettings($_SESSION['USERDATA']['id'], $_REQUEST['data'])) {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Updated notification settings', 'TYPE' => 'success');
        } else {
          $_SESSION['POPUP'][] = array('CONTENT' => $notification->getError(), 'TYPE' => 'errormsg');
        }
      } else {
        $img = $csrftoken->getDescriptionImageHTML();
        $_SESSION['POPUP'][] = array('CONTENT' => "Notification token expired, please try again $img", 'TYPE' => 'info');
      }
    }

    // Fetch notifications
    $aNotifications = $notification->getNofifications($_SESSION['USERDATA']['id']);
    if (!$aNotifications) $_SESSION['POPUP'][] = array('CONTENT' => 'Could not find any notifications', 'TYPE' => 'errormsg');

    // Fetch user notification settings
    $aSettings = $notification->getNotificationSettings($_SESSION['USERDATA']['id']);

    $smarty->assign('NOTIFICATIONS', $aNotifications);
    $smarty->assign('SETTINGS', $aSettings);
    $smarty->assign('CONTENT', 'default.tpl');
    // csrf token
    if ($csrfenabled) {
      $token = $csrftoken->getBasic($user->getCurrentIP(), 'editnotifs', 'mdyH');
      $smarty->assign('CTOKEN', $token);
    }
  }
}
?>
