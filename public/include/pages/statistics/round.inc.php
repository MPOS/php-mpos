<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);

  if (@$_REQUEST['next'] && !empty($_REQUEST['height'])) {
    $iKey = $roundstats->getNextBlock($_REQUEST['height']);
  } else if (@$_REQUEST['prev'] && !empty($_REQUEST['height'])) {
    $iKey = $roundstats->getPreviousBlock($_REQUEST['height']);
  } else {  

    if (empty($_REQUEST['height'])) {
      $iBlock = $block->getLast();
      $iKey = $iBlock['height'];
    } else {
      $iKey = $_REQUEST['height'];
    }
  }
  $aDetailsForBlockHeight = $roundstats->getDetailsForBlockHeight($iKey, $user->isAdmin(@$_SESSION['USERDATA']['id']));
  $aRoundShareStats = $roundstats->getRoundStatsForAccounts($iKey, $user->isAdmin(@$_SESSION['USERDATA']['id']));

  if ($user->isAdmin(@$_SESSION['USERDATA']['id']) || $setting->getValue('acl_round_transactions')) {
    $aUserRoundTransactions = $roundstats->getAllRoundTransactions($iKey, @$_SESSION['USERDATA']['id']);
  } else {
    $aUserRoundTransactions = $roundstats->getUserRoundTransactions($iKey, @$_SESSION['USERDATA']['id']);
  }

  // Propagate content our template
  $smarty->assign('BLOCKDETAILS', $aDetailsForBlockHeight);
  $smarty->assign('ROUNDSHARES', $aRoundShareStats);
  $smarty->assign("ROUNDTRANSACTIONS", $aUserRoundTransactions);
} else {
  $debug->append('Using cached page', 3);
}

if ($setting->getValue('acl_round_statistics')) {
  $smarty->assign("CONTENT", "default.tpl");
} else if ($user->isAuthenticated(false)) {
  $smarty->assign("CONTENT", "default.tpl");
} else {
  $smarty->assign("CONTENT", "empty");
}
?>
