<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Globally available variables
$debug->append('Global smarty variables', 3);

$aGlobal = array(
  'userdata' => $_SESSION['USERDATA']['id'] ? $user->getUserData($_SESSION['USERDATA']['id']) : array(),
  'slogan' => $settings->getValue('slogan'),
  'websitename' => $settings->getValue('websitename'),
  'ltc_usd' => $settings->getValue('btcesell'),
  'hashrate' => $settings->getValue('currenthashrate'),
  'workers' => $worker->getCountAllActiveWorkers(),
  'currentroundshares' => $settings->getValue('currentroundshares'),
  'statstime' => $settings->getValue('statstime'),
  'motd' => $settings->getValue('motd'),
  'confirmations' => $config['confirmations']
);
// Append additional user information not from user table
$aGlobal['userdata']['balance'] = $transaction->getBalance($_SESSION['USERDATA']['id']);

// Make it available in Smarty
$smarty->assign('PATH', 'site_assets/' . THEME);
$smarty->assign('GLOBAL', $aGlobal);
?>
