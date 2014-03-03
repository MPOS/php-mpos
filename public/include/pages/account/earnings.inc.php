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
    $aTransactionSummary = $transaction->getTransactionSummary($_SESSION['USERDATA']['id']);
    
    $aCredit = $transaction->getTransactionTypebyTime($_SESSION['USERDATA']['id'], 'CREDIT');
    $aDebitAP = $transaction->getTransactionTypebyTime($_SESSION['USERDATA']['id'], 'DEBIT_AP');
    $aDebitMP = $transaction->getTransactionTypebyTime($_SESSION['USERDATA']['id'], 'DEBIT_MP');
    $aTXFee = $transaction->getTransactionTypebyTime($_SESSION['USERDATA']['id'], 'TXFee');
    $aFee = $transaction->getTransactionTypebyTime($_SESSION['USERDATA']['id'], 'Fee');
    $aDonation = $transaction->getTransactionTypebyTime($_SESSION['USERDATA']['id'], 'Donation');
    
    $smarty->assign('SUMMARY', $aTransactionSummary);
    $smarty->assign('CREDIT', $aCredit);
    $smarty->assign('DEBITAP', $aDebitAP);
    $smarty->assign('DEBITMP', $aDebitMP);
    $smarty->assign('TXFEE', $aTXFee);
    $smarty->assign('FEE', $aFee);
    $smarty->assign('DONATION', $aDonation);
  }
} else {
  $debug->append('Using cached page', 3);
}

$smarty->assign('CONTENT', 'default.tpl');
?>
