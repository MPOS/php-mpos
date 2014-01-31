<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

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
