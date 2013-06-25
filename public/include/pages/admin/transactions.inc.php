<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');
if ($user->isAuthenticated()) {
  $aTransactions = $transaction->getAllTransactions();
  if (!$aTransactions) $_SESSION['POPUP'][] = array('CONTENT' => 'Could not find any transaction', 'TYPE' => 'errormsg');
  $smarty->assign('TRANSACTIONS', $aTransactions);
  $smarty->assign('CONTENT', 'default.tpl');
}
?>
