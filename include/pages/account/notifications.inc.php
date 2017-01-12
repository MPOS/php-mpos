<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($user->isAuthenticated()) {
  if ($setting->getValue('disable_notifications') == 1) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Notification system disabled by admin.', 'TYPE' => 'alert alert-warning');
    $smarty->assign('CONTENT', 'empty');
  } else {
    if (@$_REQUEST['do'] == 'save') {
      if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {

      	$pushSettings = array(
      		'class' => $_REQUEST['pushnotification-class'],
      		'params' => null,
      		'file' => null,
      	);
      	if ($pushSettings['class'] && array_key_exists($pushSettings['class'], $_REQUEST['pushnotification'])){
      		$pushSettings['params'] = $_REQUEST['pushnotification'][$pushSettings['class']];
      	}
      	if ($pushSettings['class']){
      		$c = $pushnotification->getClasses();
      		if (array_key_exists($pushSettings['class'], $c)){
      			$pushSettings['file'] = $c[$pushSettings['class']][0];
      		}
      	}
      	
      	if (!$pushnotification->updateSettings($_SESSION['USERDATA']['id'], $pushSettings)){
      		$_SESSION['POPUP'][] = array('CONTENT' => $pushnotification->getError(), 'TYPE' => 'alert alert-danger');
      	}elseif ($notification->updateSettings($_SESSION['USERDATA']['id'], $_REQUEST['data'])) {
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
    $aPushSettings = $pushnotification->getNotificationSettings($_SESSION['USERDATA']['id']);
    $aSmartyClasses = $pushnotification->getClassesForSmarty();

    $smarty->assign('NOTIFICATIONS', $aNotifications);
    $smarty->assign('PUSHNOTIFICATIONS', $aSmartyClasses);
    $smarty->assign('PUSHSETTINGS', $aPushSettings);
    $smarty->assign('SETTINGS', $aSettings);
    $smarty->assign('CONTENT', 'default.tpl');
  }
}
