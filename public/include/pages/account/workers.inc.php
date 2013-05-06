<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');
if (!$_SESSION['AUTHENTICATED']) header('Location: index.php?page=home');

switch ($_REQUEST['do']) {
case 'delete':
  if ($user->deleteWorker($_SESSION['USERDATA']['id'], $_GET['id'])) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Worker removed');
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => $user->error, 'TYPE' => 'errormsg');
  }
  break;
case 'add':
  if ($user->addWorker($_SESSION['USERDATA']['id'], $_POST['username'], $_POST['password'])) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Worker added');
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => $user->error, 'TYPE' => 'errormsg');
  }
  break;
case 'update':
  if ($user->updateWorkers($_SESSION['USERDATA']['id'], $_POST['data'])) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Worker updated');
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => $user->error, 'TYPE' => 'errormsg');
  }
  break;
}

$aWorkers = $user->getWorkers($_SESSION['USERDATA']['id']);
if (!$aWorkers) $_SESSION['POPUP'][] = array('CONTENT' => 'You have no workers configured', 'TYPE' => 'errormsg');

$smarty->assign('CONTENT', 'default.tpl');
$smarty->assign('WORKERS', $aWorkers);
?>
