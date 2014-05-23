<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

// Include markdown library
use \Michelf\Markdown;

if (@$_REQUEST['do'] == 'save') {
  if ($news->updateNews($_REQUEST['id'], $_REQUEST['header'], $_REQUEST['content'], $_REQUEST['active'])) {
    $_SESSION['POPUP'][] = array('CONTENT' => 'News updated', 'TYPE' => 'alert alert-success');
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => 'News update failed: ' . $news->getError(), 'TYPE' => 'alert alert-danger');
  }
}

// Fetch news entry
$aNews = $news->getEntry($_REQUEST['id']);
$smarty->assign("NEWS", $aNews);
$smarty->assign("CONTENT", "default.tpl");
?>
