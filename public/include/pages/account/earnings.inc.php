<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $iLimit = 30;
  $debug->append('No cached version available, fetching from backend', 3);
  if (!$setting->getValue('disable_transactionsummary')) {
    $aTransactionSummary = $transaction->getTransactionSummarybyTime($_SESSION['USERDATA']['id']);
    $smarty->assign('SUMMARY', $aTransactionSummary);
  }
} else {
  $debug->append('Using cached page', 3);
}

$smarty->assign('CONTENT', 'default.tpl');
?>
