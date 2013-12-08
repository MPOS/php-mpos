<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Include markdown library
use \Michelf\Markdown;

// Fetch active news to display
$aNews = $news->getAllActive();
if (is_array($aNews)) {
  foreach ($aNews as $key => $aData) {
    // Transform Markdown content to HTML
    $aNews[$key]['content'] = Markdown::defaultTransform($aData['content']);
  }
}

// Tempalte specifics
$smarty->assign("HIDEAUTHOR", $settings->getValue('acl_hide_news_author'));
$smarty->assign("NEWS", $aNews);
$smarty->assign("CONTENT", "default.tpl");
?>
