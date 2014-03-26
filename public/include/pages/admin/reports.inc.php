<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);

  $aUserList = $user->getAllUsers('%');

  $iHeight = 0;
  $iUserId = 0;
  $filter = 0;
  $userName = 'None';
  if (@$_REQUEST['id']) {
    $iUserId = $_REQUEST['id'];
    $userName = $user->getUserName($_REQUEST['id']);
  }

  $setting->getValue('statistics_block_count') ? $iLimit = $setting->getValue('statistics_block_count') : $iLimit = 20;
  if (@$_REQUEST['limit']) {
    $iLimit = $_REQUEST['limit'];
      if ( $iLimit > 1000 )
        $iLimit = 1000;
  }

  if (@$_REQUEST['search']) {
    $iHeight = $roundstats->searchForBlockHeight($_REQUEST['search']);
  }
  if (@$_REQUEST['next'] && !empty($_REQUEST['height'])) {
    $iHeight = @$roundstats->getNextBlock($_REQUEST['height']);
    if (!$iHeight) {
      $iBlock = $block->getLast();
      $iHeight = $iBlock['height'];
    }
  } else if (@$_REQUEST['prev'] && !empty($_REQUEST['height'])) {
    $iHeight = $roundstats->getPreviousBlock($_REQUEST['height']);
  } else if (empty($_REQUEST['height'])) {
    $iBlock = $block->getLast();
    $iHeight = $iBlock['height'];
  } else {
    $iHeight = $_REQUEST['height'];
  }
  $_REQUEST['height'] = $iHeight;

  if (@$_REQUEST['filter']) {
    $filter = $_REQUEST['filter'];
  }

  $aBlocksData = array();
  if ( $iUserId ) {
    if ($filter) { 
      $aBlocksData = $roundstats->getAllReportBlocksFoundHeight($iHeight, $iLimit);
    } else {
      $aBlocksData = $roundstats->getUserReportBlocksFoundHeight($iHeight, $iLimit, $iUserId);
    }
    foreach($aBlocksData as $key => $aData) {
      $aBlocksData[$key]['pplns_shares'] = @$roundstats->getPPLNSRoundShares($aData['height']);
      $aBlocksData[$key]['user'] = @$roundstats->getRoundStatsForUser($aData['height'], $iUserId);
      $aBlocksData[$key]['user_credit'] = @$roundstats->getUserRoundTransHeight($aData['height'], $iUserId);
    }
  }

  $smarty->assign('REPORTDATA', $aBlocksData);
  $smarty->assign("USERLIST", $aUserList);
  $smarty->assign("USERNAME", $userName);
  $smarty->assign("USERID", $iUserId);
  $smarty->assign("BLOCKLIMIT", $iLimit);
  $smarty->assign("HEIGHT", $iHeight);
  $smarty->assign("FILTER", $filter);
} else {
  $debug->append('Using cached page', 3);
}

if ($user->isAuthenticated(false)) {
  $smarty->assign("CONTENT", "default.tpl");
} else {
  $smarty->assign("CONTENT", "empty");
}
?>
