<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user authentication status
if ($user->isAuthenticated()) {
  if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
    $iLimit = 30;
    $debug->append('No cached version available, fetching from backend', 3);
    if (!$setting->getValue('disable_transactionsummary')) {
      $aTransactionSummary = $transaction->getTransactionSummary($_SESSION['USERDATA']['id']);
      $aTransactionSummaryByTime = $transaction->getTransactionTypebyTime($_SESSION['USERDATA']['id']);
      $smarty->assign('SUMMARY', $aTransactionSummary);
      $smarty->assign('BYTIME', $aTransactionSummaryByTime);
    }
  } else {
    $debug->append('Using cached page', 3);
  }
}

$smarty->assign('CONTENT', 'default.tpl');
?>
