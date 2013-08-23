<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);

if (@$_REQUEST['next'] && !empty($_REQUEST['tx_key'])) {
  $_REQUEST['tx_key'] = $roundstats->getNextBlockDesc($_REQUEST['tx_key']);
} else if (@$_REQUEST['prev'] && !empty($_REQUEST['tx_key'])) {
  $_REQUEST['tx_key'] = $roundstats->getNextBlockAsc($_REQUEST['tx_key']);
}   

if (empty($_REQUEST['tx_key'])) {
  $iBlock = $block->getLast();
  $iKey = $iBlock['height'];
  $_REQUEST['tx_key'] = $iKey;
} else {
  $iKey = $_REQUEST['tx_key'];
}

  $aDetailsForBlockHeight = $roundstats->getDetailsForBlockHeight($iKey, $user->isAdmin($_SESSION['USERDATA']['id']));
  $aRoundShareStats = $roundstats->getRoundStatsForAccounts($iKey, $user->isAdmin($_SESSION['USERDATA']['id']));

if ($user->isAdmin($_SESSION['USERDATA']['id'])) {
  $aUserRoundTransactions = $roundstats->getAllRoundTransactions($iKey);
} else {
  $aUserRoundTransactions = $roundstats->getUserRoundTransactions($iKey, $_SESSION['USERDATA']['id']);
}

  // Propagate content our template
  $smarty->assign('BLOCKDETAILS', $aDetailsForBlockHeight);
  $smarty->assign('ROUNDSHARES', $aRoundShareStats);
  $smarty->assign("ROUNDTRANSACTIONS", $aUserRoundTransactions);
} else {
  $debug->append('Using cached page', 3);
}

$smarty->assign("CONTENT", "default.tpl");
?>
