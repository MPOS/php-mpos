<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

if (!$smarty->isCached('master.tpl', md5(serialize($_REQUEST)))) {
  $debug->append('No cached version available, fetching from backend', 3);
  $aTransactions = $transaction->getAllTransactions(@$_REQUEST['start']);
  if (!$aTransactions) $_SESSION['POPUP'][] = array('CONTENT' => 'Could not find any transaction', 'TYPE' => 'errormsg');
} else {
  $debug->append('Using cached page', 3);
}
$smarty->assign('TRANSACTIONS', $aTransactions);
$smarty->assign('CONTENT', 'default.tpl');
?>
