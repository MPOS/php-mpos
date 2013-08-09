<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');
if ($user->isAuthenticated()) {
  $iLimit = 30;
  empty($_REQUEST['start']) ? $start = 0 : $start = $_REQUEST['start'];
  $aTransactions = $transaction->getTransactions($start, @$_REQUEST['filter'], $iLimit, $_SESSION['USERDATA']['id']);
  $aTransactionSummary = $transaction->getTransactionSummary($_SESSION['USERDATA']['id']);
  $iCountTransactions = $transaction->num_rows;
  $aTransactionTypes = $transaction->getTypes();
  if (!$aTransactions) $_SESSION['POPUP'][] = array('CONTENT' => 'Could not find any transaction', 'TYPE' => 'errormsg');
  $smarty->assign('LIMIT', $iLimit);
  $smarty->assign('TRANSACTIONS', $aTransactions);
  $smarty->assign('SUMMARY', $aTransactionSummary);
  $smarty->assign('TRANSACTIONTYPES', $aTransactionTypes);
  $smarty->assign('TXSTATUS', array('' => '', 'Confirmed' => 'Confirmed', 'Unconfirmed' => 'Unconfirmed', 'Orphan' => 'Orphan'));
  $smarty->assign('COUNTTRANSACTIONS', $iCountTransactions);
}
$smarty->assign('CONTENT', 'default.tpl');
?>
