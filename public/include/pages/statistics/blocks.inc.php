<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

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
  if (@$_REQUEST['next'] && !empty($_REQUEST['height']) && is_numeric($_REQUEST['height'])) {
    $iHeight = @$roundstats->getNextBlockForStats($_REQUEST['height'], $iLimit);
      if (!$iHeight) {
        $iBlock = $block->getLast();
        $iHeight = $iBlock['height']; 
      }
  } else if (@$_REQUEST['prev'] && !empty($_REQUEST['height']) && is_numeric($_REQUEST['height'])) {
    $iHeight = $_REQUEST['height'];
  } else if (empty($_REQUEST['height'])) {
      $aBlock = $block->getLast();
      $iHeight = $aBlock['height']; 
  }

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

  // Propagate content our template
  $smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
  $smarty->assign("BLOCKLIMIT", $iLimit);
  $smarty->assign("USEBLOCKAVERAGE", $use_average);
  $smarty->assign("POOLSTATS", $aPoolStatistics);
} else {
  $debug->append('Using cached page', 3);
}

if ($setting->getValue('acl_block_statistics')) {
  $smarty->assign("CONTENT", "default.tpl");
} else if ($user->isAuthenticated()) {
  $smarty->assign("CONTENT", "default.tpl");
}
?>
