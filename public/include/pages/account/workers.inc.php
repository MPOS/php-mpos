<?php
// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($user->isAuthenticated()) {
  // csrf stuff
  $csrfenabled = ($config['csrf']['enabled'] && !in_array('workers', $config['csrf']['disabled_forms'])) ? 1 : 0;
  if ($csrfenabled) {
    $nocsrf = ($csrftoken->getBasic($user->getCurrentIP(), 'workers') == @$_POST['ctoken']) ? 1 : 0;
  }
  
  switch (@$_REQUEST['do']) {
  case 'delete':
    if ($worker->deleteWorker($_SESSION['USERDATA']['id'], $_GET['id'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Worker removed', 'TYPE' => 'success');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $worker->getError(), 'TYPE' => 'errormsg');
    }
    break;
  case 'add':
    if (!$csrfenabled || $csrfenabled && $nocsrf) {
      if ($worker->addWorker($_SESSION['USERDATA']['id'], $_POST['username'], $_POST['password'])) {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Worker added', 'TYPE' => 'success');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => $worker->getError(), 'TYPE' => 'errormsg');
      }
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'info');
    }
    break;
  case 'update':
    if (!$csrfenabled || $csrfenabled && $nocsrf) {
      if ($worker->updateWorkers($_SESSION['USERDATA']['id'], @$_POST['data'])) {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Worker updated', 'TYPE' => 'success');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => $worker->getError(), 'TYPE' => 'errormsg');
      }
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'info');
    }
    break;
  }

  $aWorkers = $worker->getWorkers($_SESSION['USERDATA']['id']);
  if (!$aWorkers) $_SESSION['POPUP'][] = array('CONTENT' => 'You have no workers configured', 'TYPE' => 'errormsg');

  $smarty->assign('WORKERS', $aWorkers);
}
// csrf token
if ($csrfenabled && !in_array('workers', $config['csrf']['disabled_forms'])) {
  $token = $csrftoken->getBasic($user->getCurrentIP(), 'workers');
  $smarty->assign('CTOKEN', $token);
}
$smarty->assign('CONTENT', 'default.tpl');
?>
