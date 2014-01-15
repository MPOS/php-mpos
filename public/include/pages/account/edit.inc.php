<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

if ($user->isAuthenticated()) {
  if (isset($_POST['do']) && $_POST['do'] == 'genPin') {
    if ($user->generatePin($_SESSION['USERDATA']['id'], $_POST['currentPassword'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Your PIN # has been sent to your email.', 'TYPE' => 'success');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $user->getError(), 'TYPE' => 'errormsg');
    }
  }
  else {
    if ( @$_POST['do'] && (! $user->checkPin($_SESSION['USERDATA']['id'], @$_POST['authPin']))) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Invalid PIN. ' . ($config['maxfailed']['pin'] - $user->getUserPinFailed($_SESSION['USERDATA']['id'])) . ' attempts remaining.', 'TYPE' => 'errormsg');
    } else {
      switch (@$_POST['do']) {
      case 'cashOut':
        if ($setting->getValue('disable_payouts') == 1 || $setting->getValue('disable_manual_payouts') == 1) {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Manual payouts are disabled.', 'TYPE' => 'info');
        } else {
          $aBalance = $transaction->getBalance($_SESSION['USERDATA']['id']);
          $dBalance = $aBalance['confirmed'];
          if ($dBalance > $config['txfee']) {
            if (!$oPayout->isPayoutActive($_SESSION['USERDATA']['id'])) {
              $wf_token = (!isset($_POST['wf_token'])) ? '' : $_POST['wf_token'];
              if ($iPayoutId = $oPayout->createPayout($_SESSION['USERDATA']['id'], $wf_token)) {
                $_SESSION['POPUP'][] = array('CONTENT' => 'Created new manual payout request with ID #' . $iPayoutId);
              } else {
                $_SESSION['POPUP'][] = array('CONTENT' => $iPayoutId->getError(), 'TYPE' => 'errormsg');
              }
            } else {
              $_SESSION['POPUP'][] = array('CONTENT' => 'You already have one active manual payout request.', 'TYPE' => 'errormsg');
            }
          } else {
            $_SESSION['POPUP'][] = array('CONTENT' => 'Insufficient funds, you need more than ' . $config['txfee'] . ' ' . $config['currency'] . ' to cover transaction fees', 'TYPE' => 'errormsg');
          }
        }
        break;

      case 'updateAccount':
        $ea_token = (!isset($_POST['ea_token'])) ? '' : $_POST['ea_token'];
        if ($user->updateAccount($_SESSION['USERDATA']['id'], $_POST['paymentAddress'], $_POST['payoutThreshold'], $_POST['donatePercent'], $_POST['email'], $_POST['is_anonymous'], $ea_token)) {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Account details updated', 'TYPE' => 'success');
        } else {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Failed to update your account: ' . $user->getError(), 'TYPE' => 'errormsg');
        }
        break;

      case 'updatePassword':
        $cp_token = (!isset($_POST['cp_token'])) ? '' : $_POST['cp_token'];
        if ($user->updatePassword($_SESSION['USERDATA']['id'], $_POST['currentPassword'], $_POST['newPassword'], $_POST['newPassword2'], $cp_token)) {
          $_SESSION['POPUP'][] = array('CONTENT' => 'Password updated', 'TYPE' => 'success');
        } else {
          $_SESSION['POPUP'][] = array('CONTENT' => $user->getError(), 'TYPE' => 'errormsg');
        }
        break;
      }
    }
  }
}
// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>