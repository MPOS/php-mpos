<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

$aRoundShares = $statistics->getRoundShares();

switch (@$_POST['do']) {
case 'lock':
  $supress_master = 1;
  $user->changeLocked($_POST['account_id']);
  break;
case 'fee':
  $supress_master = 1;
  $user->changeNoFee($_POST['account_id']);
  break;
case 'admin':
  $supress_master = 1;
  $user->changeAdmin($_POST['account_id']);
  break;
}

if (@$_POST['query']) {
  // Fetch requested users
  $aUsers = $statistics->getAllUserStats($_POST['query']);

  // Add additional stats to each user
  // This is not optimized yet, best is a proper SQL
  // Query against the stats table? Currently cached though.
  foreach ($aUsers as $iKey => $aUser) {
    $aBalance = $transaction->getBalance($aUser['id']);
    $aUser['balance'] = $aBalance['confirmed'];
    $aUser['hashrate'] = $statistics->getUserHashrate($aUser['id']);
    if ($aUser['shares'] > 0) {
      $aUser['payout']['est_block'] = round(( (int)$aUser['shares'] / (int)$aRoundShares['valid'] ) * (int)$config['reward'], 3);
      $aUser['payout']['est_fee'] = round(($config['fees'] / 100) * $aUser['payout']['est_block'], 3);
      $aUser['payout']['est_donation'] = round((( $aUser['donate_percent'] / 100) * ($aUser['payout']['est_block'] - $aUser['payout']['est_fee'])), 3);
      $aUser['payout']['est_payout'] = round($aUser['payout']['est_block'] - $aUser['payout']['est_donation'] - $aUser['payout']['est_fee'], 3);
    } else {
      $aUser['payout']['est_block'] = 0;
      $aUser['payout']['est_fee'] = 0;
      $aUser['payout']['est_donation'] = 0;
      $aUser['payout']['est_payout'] = 0;
    }
    $aUsers[$iKey] = $aUser;
  }
  // Assign our variables
  $smarty->assign("USERS", $aUsers);
}


// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
