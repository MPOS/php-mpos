<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

//Fetch all lists
$aPages = @$pageModel->getAllAsHash();
if ( $aPages === false ) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Can\'t get pages. Have you created `pages` table? Run 004_create_pages_table.sql from sql folder', 'TYPE' => 'errormsg');
  $smarty->assign("CONTENT", "empty");
} else {
  $aTemplates = array('' => 'Common', 'mpos' => 'mpos', 'mmcFE' => 'mmcFE', 'mobile' => 'mobile');

  //Fetch current slug and template
  $sPageSlug = @$_REQUEST['slug'];
  if(!array_key_exists($sPageSlug, $aPages)) {
    $sPageSlug = array_keys($aPages)[0];
  }
  $sTemplate = @$_REQUEST['template'];
  if(!$sTemplate || !array_key_exists($sTemplate, $aTemplates)) {
    $sTemplate = '';
  }

  if (@$_REQUEST['do'] == 'save') {
    if ($pageModel->updatePage($_REQUEST['slug'], $_REQUEST['template'], $_REQUEST['content'], $_REQUEST['active'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Page updated', 'TYPE' => 'success');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Page update failed: ' . $news->getError(), 'TYPE' => 'errormsg');
    }
  }

  //Fetch the entry
  $oPage = $pageModel->getEntry($sPageSlug, $sTemplate);

  $smarty->assign("PAGES", $aPages);
  $smarty->assign("TEMPLATES", $aTemplates);
  $smarty->assign("CURRENT_PAGE", $oPage);
  $smarty->assign("CONTENT", "default.tpl");
}
?>
