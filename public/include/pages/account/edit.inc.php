<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

if (!$_SESSION['AUTHENTICATED']) {
  header('Location: index.php?page=home');
}

if ( ! $user->checkPin($_SESSION['USERDATA']['id'], $_POST['authPin']) && $_POST['do']) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Invalid PIN','TYPE' => 'errormsg');
} else {
  switch ($_POST['do']) {
  case 'cashOut':
    $dUserBalance = $user->getBalance($_SESSION['USERDATA']['id']);
    $sUserSendAddress = $user->getLtcAddress($_SESSION['USERDATA']['id']);
    $dUserPaid = $user->getPaid($_SESSION['USERDATA']['id']);
    if ($dUserBalance > 0.1) {
      if ($bitcoin->can_connect() === true) {
        try {
          $bitcoin->validateaddress($sUserSendAddress);
        } catch (BitcoinClientException $e) {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Invalid payment address: ' . $sUserSendAddress, 'TYPE' => 'errormsg');
        }
        // Remove the transfer fee
        $dUserBalance = $dUserBalance - 0.1;
        try {
          $bitcoin->sendtoaddress($sUserSendAddress, $dUserBalance);
        } catch (BitcoinClientException $e) {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Failed to send LTC, please contact site support immidiately', 'TYPE' => 'errormsg');
        }
        // Set balance to 0, add to paid out, insert to ledger
        if ( $user->setBalance($_SESSION['USERDATA']['id'], 0) && 
          $user->setPaid($_SESSION['USERDATA']['id'], $dUserPaid + $dUserBalance) && 
          $user->addLedger($_SESSION['USERDATA']['id'], $dUserBalance, $sUserSendAddress) ) {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Transaction completed', 'TYPE' => 'success');
        }
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to pushpool service', 'TYPE' => 'errormsg');
      }
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Insufficient funds, you need more than 0.1 LTC to cover transaction fees', 'TYPE' => 'errormsg');
    }
    break;

  case 'updateAccount':
    if ($user->updateAccount($_SESSION['USERDATA']['id'], $_POST['paymentAddress'], $_POST['payoutThreshold'], $_POST['donatePercent'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Account details updated', 'TYPE' => 'success');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Failed to update your account', 'TYPE' => 'errormsg');
    }
    break;

  case 'updatePassword':
    if ($user->updatePassword($_SESSION['USERDATA']['id'], $_POST['currentPassword'], $_POST['newPassword'], $_POST['newPassword2'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Password updated', 'TYPE' => 'success');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $user->error, 'TYPE' => 'errormsg');
    }
    break;
  }
}
// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
