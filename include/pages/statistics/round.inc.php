<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);

  if (@$_REQUEST['search']) {
    $_REQUEST['height'] = $roundstats->searchForBlockHeight($_REQUEST['search']);
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

  $iPPLNSShares = 0;
  $aDetailsForBlockHeight = $roundstats->getDetailsForBlockHeight($iHeight);
  $aRoundShareStats = $roundstats->getRoundStatsForAccounts($iHeight);
  $aUserRoundTransactions = $roundstats->getAllRoundTransactions($iHeight);

  if ($config['payout_system'] == 'pplns') {
    $aPPLNSRoundShares = $roundstats->getPPLNSRoundStatsForAccounts($iHeight);
    foreach($aPPLNSRoundShares as $aData) {
      $iPPLNSShares += $aData['pplns_valid'];
    }
    $block_avg = $block->getAvgBlockShares($iHeight, $config['pplns']['blockavg']['blockcount']);
    $smarty->assign('PPLNSROUNDSHARES', $aPPLNSRoundShares);
    $smarty->assign("PPLNSSHARES", $iPPLNSShares);
    $smarty->assign("BLOCKAVGCOUNT", $config['pplns']['blockavg']['blockcount']);
    $smarty->assign("BLOCKAVERAGE", $block_avg );
  }

  // Propagate content our template
  $smarty->assign('BLOCKDETAILS', $aDetailsForBlockHeight);
  $smarty->assign('ROUNDSHARES', $aRoundShareStats);
  $smarty->assign("ROUNDTRANSACTIONS", $aUserRoundTransactions);
} else {
  $debug->append('Using cached page', 3);
}

switch($setting->getValue('acl_round_statistics', 1)) {
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
