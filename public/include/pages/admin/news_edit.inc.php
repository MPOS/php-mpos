<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Include markdown library
use \Michelf\Markdown;

if (@$_REQUEST['do'] == 'save') {
 if ($news->updateNews($_REQUEST['id'], $_REQUEST['header'], $_REQUEST['content'], $_REQUEST['active'])) {
   $_SESSION['POPUP'][] = array('CONTENT' => 'News updated', 'TYPE' => 'success');
 } else {
   $_SESSION['POPUP'][] = array('CONTENT' => 'News update failed: ' . $news->getError(), 'TYPE' => 'errormsg');
 }
}

// Fetch news entry
$aNews = $news->getEntry($_REQUEST['id']);
$smarty->assign("NEWS", $aNews);
$smarty->assign("CONTENT", "default.tpl");
?>
