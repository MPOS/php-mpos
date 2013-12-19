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
  // Reset user account
  $user->changeLocked($_POST['account_id']);
  if ($user->isLocked($_POST['account_id']) == 0) {
    $user->setUserFailed($_POST['account_id'], 0);
    $user->setUserPinFailed($_POST['account_id'], 0);
  }
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
  foreach ($aUsers as $iKey => $aUser) {
    $aBalance = $transaction->getBalance($aUser['id']);
    $aUser['balance'] = $aBalance['confirmed'];
    $aUser['hashrate'] = $statistics->getUserHashrate($aUser['id']);

    if ($config['payout_system'] == 'pps') {
      $aUser['sharerate'] = $statistics->getUserSharerate($aUser['id']);
      $aUser['difficulty'] = $statistics->getUserShareDifficulty($aUser['id']);
      $aUser['estimates'] = $statistics->getUserEstimates($aUser['sharerate'], $aUser['difficulty'], $user->getUserDonatePercent($aUser['id']), $user->getUserNoFee($aUser['id']), $statistics->getPPSValue());
    } else {
      $aUser['estimates'] = $statistics->getUserEstimates($aRoundShares, $aUser['shares'], $aUser['donate_percent'], $aUser['no_fees']);
    }
    $aUsers[$iKey] = $aUser;
  }
  // Assign our variables
  $smarty->assign("USERS", $aUsers);
}


// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
