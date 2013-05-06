<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');
if (!$_SESSION['AUTHENTICATED']) header('Location: index.php?page=home');

$aTransactions = $user->getTransactions($_SESSION['USERDATA']['id']);
if (!$aTransactions) $_SESSION['POPUP'][] = array('CONTENT' => 'Could not find any transaction', 'TYPE' => 'errormsg');

$smarty->assign('TRANSACTIONS', $aTransactions);
$smarty->assign('CONTENT', 'default.tpl');
?>
