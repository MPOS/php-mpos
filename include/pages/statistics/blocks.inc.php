<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Grab the last blocks found
if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  // Grab the last blocks found
  $setting->getValue('statistics_block_count') ? $iLimit = $setting->getValue('statistics_block_count') : $iLimit = 20;
  if (@$_REQUEST['limit'] && !empty($_REQUEST['limit']) && is_numeric($_REQUEST['limit'])) {
    $iLimit = $_REQUEST['limit'];
      if ( $iLimit > 40 )
        $iLimit = 40;
  }

  $iHeight = 0;
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

  $test = false;
  if (@$_REQUEST['test'] && $user->isAdmin($_SESSION['USERDATA']['id'])) {
    $test = true;
    $count = 10;
    $percent = 30;
    if (@$_REQUEST['count'] && is_numeric($_REQUEST['count']))
      $count = $_REQUEST['count'];
    if (@$_REQUEST['percent'] && is_numeric($_REQUEST['percent']))
      $percent = $_REQUEST['percent'];
  }

  $aBlocksFoundData = $statistics->getBlocksFoundHeight($iHeight, $iLimit);
  $use_average = false;
  if ($config['payout_system'] == 'pplns') {
    foreach($aBlocksFoundData as $key => $aData) {
      $aBlocksFoundData[$key]['pplns_shares'] = $roundstats->getPPLNSRoundShares($aData['height']);
      if ($setting->getValue('statistics_show_block_average') && !$test) {
        $aBlocksFoundData[$key]['block_avg'] = round($block->getAvgBlockShares($aData['height'], $config['pplns']['blockavg']['blockcount']));
        $use_average = true;
      }
    }
  } else if ($config['payout_system'] == 'prop' || $config['payout_system'] == 'pps') {
    if ($setting->getValue('statistics_show_block_average') && !$test) {
      foreach($aBlocksFoundData as $key => $aData) {
        $aBlocksFoundData[$key]['block_avg'] = round($block->getAvgBlockShares($aData['height'], $config['pplns']['blockavg']['blockcount']));
        $use_average = true;
      }
    }
  }
  // show test data in graph
  if ($test) {
    $use_average = true;
    foreach($aBlocksFoundData as $key => $aData) {
      if ($_REQUEST['test'] == 1) {
        $aBlocksFoundData[$key]['block_avg'] = round($block->getAvgBlockShares($aData['height'], $count));
      } else if ($_REQUEST['test'] == 2) {
        $aBlocksFoundData[$key]['block_avg'] = round($block->getAvgBlockShares($aData['height'], $count) * (100 - $percent) / 100 + $aData['shares'] * $percent / 100);
      }
    }
  }

  $iHours = 24;
  $aPoolStatistics = $statistics->getPoolStatsHours($iHours);
  $iFirstBlockFound = $statistics->getFirstBlockFound();
  $iTimeSinceFirstBlockFound = (time() - $iFirstBlockFound);

  // Coin target block generation time, default to 150 (2.5 minutes)
  @$config['cointarget'] ? $smarty->assign("COINGENTIME", $config['cointarget']) : $smarty->assign("COINGENTIME", 150);

  // Past blocks found, max 4 weeks back
  $iFoundBlocksByTime = $statistics->getLastBlocksbyTime();
  // Propagate content our template
  $smarty->assign("FIRSTBLOCKFOUND", $iTimeSinceFirstBlockFound);
  $smarty->assign("LASTBLOCKSBYTIME", $iFoundBlocksByTime);
  $smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
  $smarty->assign("BLOCKLIMIT", $iLimit);
  $smarty->assign("USEBLOCKAVERAGE", $use_average);
  $smarty->assign("POOLSTATS", $aPoolStatistics);
} else {
  $debug->append('Using cached page', 3);
}

switch($setting->getValue('acl_block_statistics', 1)) {
case '0':
  if ($user->isAuthenticated()) {
    $smarty->assign("CONTENT", "default.tpl");
  }
  break;
case '1':
  $smarty->assign("CONTENT", "default.tpl");
  break;
case '2':
  $_SESSION['POPUP'][] = array('CONTENT' => 'Page currently disabled. Please try again later.', 'TYPE' => 'alert alert-danger');
  $smarty->assign("CONTENT", "");
  break;
}
