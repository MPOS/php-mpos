<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

// Include markdown library
use \Michelf\Markdown;

if (@$_REQUEST['do'] == 'toggle_active')
  if ($news->toggleActive($_REQUEST['id']))
    $_SESSION['POPUP'][] = array('CONTENT' => 'News entry changed', 'TYPE' => 'success');

if (@$_REQUEST['do'] == 'add') {
  if ($news->addNews($_SESSION['USERDATA']['id'], $_POST['data'])) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'News entry added', 'TYPE' => 'success');
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Failed to add new entry: ' . $news->getError(), 'TYPE' => 'errormsg');
  }
}

if (@$_REQUEST['do'] == 'delete') {
  if ($news->deleteNews((int)$_REQUEST['id'])) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Succesfully removed news entry', 'TYPE' => 'success');
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Failed to delete entry: ' . $news->getError(), 'TYPE' => 'errormsg');
  }
}

// Fetch all news
$aNews = $news->getAll();
foreach ($aNews as $key => $aData) {
  // Transform Markdown content to HTML
  $aNews[$key]['content'] = Markdown::defaultTransform($aData['content']);
}
$smarty->assign("NEWS", $aNews);
$smarty->assign("CONTENT", "default.tpl");
?>
