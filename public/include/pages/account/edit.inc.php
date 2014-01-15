<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

// 2fa tpl stuff
$cp_editable = $wf_editable = $ea_editable = $wf_sent = $ea_sent = $cp_sent = 0;
$ea_token = (!isset($_GET['ea_token'])) ? '' : $_GET['ea_token'];
$cp_token = (!isset($_GET['cp_token'])) ? '' : $_GET['cp_token'];
$wf_token = (!isset($_GET['wf_token'])) ? '' : $_GET['wf_token'];
if ($user->isAuthenticated()) {
  // update 2f tpl stuff
  if ($config['twofactor']['enabled']) {
    $popupmsg = 'E-mail confirmations are required for ';
    $popuptypes = array();
    if ($config['twofactor']['options']['details']) {
      $popuptypes[] = 'editing your details';
      $ea_editable = $user->token->isTokenValid($_SESSION['USERDATA']['id'], $ea_token, 5);
      $ea_sent = $user->token->doesTokenExist('account_edit', $_SESSION['USERDATA']['id']);
    }
    if ($config['twofactor']['options']['changepw']) {
      $popuptypes[] = 'changing your password';
      $cp_editable = $user->token->isTokenValid($_SESSION['USERDATA']['id'], $cp_token, 6);
      $cp_sent = $user->token->doesTokenExist('change_pw', $_SESSION['USERDATA']['id']);
    }
    if ($config['twofactor']['options']['withdraw']) {
      $popuptypes[] = 'withdrawals';
      $wf_editable = $user->token->isTokenValid($_SESSION['USERDATA']['id'], $wf_token, 7);
      $wf_sent = $user->token->doesTokenExist('withdraw_funds', $_SESSION['USERDATA']['id']);
    }
    $ptc = 0;
    $ptcn = count($popuptypes);
    foreach ($popuptypes as $pt) {
      if ($ptcn == 1) { $popupmsg.= $popuptypes[$ptc]; continue; }
      if ($ptc !== ($ptcn-1)) {
        $popupmsg.= $popuptypes[$ptc].', ';
      } else {
        $popupmsg.= 'and '.$popuptypes[$ptc];
      }
      $ptc++;
    }
    $_SESSION['POPUP'][] = array('CONTENT' => $popupmsg, 'TYPE' => 'info');
  }
  if (isset($_POST['do']) && $_POST['do'] == 'genPin') {
    if ($user->generatePin($_SESSION['USERDATA']['id'], $_POST['currentPassword'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Your PIN # has been sent to your email.', 'TYPE' => 'success');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $user->getError(), 'TYPE' => 'errormsg');
    }
  }
  else {
    if ( @$_POST['do'] && (!$checkpin = $user->checkPin($_SESSION['USERDATA']['id'], @$_POST['authPin']))) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Invalid PIN. ' . ($config['maxfailed']['pin'] - $user->getUserPinFailed($_SESSION['USERDATA']['id'])) . ' attempts remaining.', 'TYPE' => 'errormsg');
    } else {
      if (isset($_POST['unlock']) && isset($_POST['utype']) && $checkpin) {
        $validtypes = array('account_edit','change_pw','withdraw_funds');
        $isvalid = in_array($_POST['utype'],$validtypes);
        if ($isvalid) {
          $ctype = strip_tags($_POST['utype']);
          $send = $user->sendChangeConf($ctype, $_SESSION['USERDATA']['id']);
          // set to sent for this pageload
          if ($ctype == 'account_edit') $ea_sent = 1;
          if ($ctype == 'change_pw') $cp_sent = 1;
          if ($ctype == 'withdraw_funds') $wf_sent = 1;
          if ($send) {
            $_SESSION['POPUP'][] = array('CONTENT' => 'A confirmation was sent to your e-mail, follow that link to continue', 'TYPE' => 'success');
          } else {
            $_SESSION['POPUP'][] = array('CONTENT' => $user->getError(), 'TYPE' => 'errormsg');
          }
        }
      } else {
        $ea_token = (!isset($_POST['ea_token'])) ? '' : $_POST['ea_token'];
        $cp_token = (!isset($_POST['cp_token'])) ? '' : $_POST['cp_token'];
        $wf_token = (!isset($_POST['wf_token'])) ? '' : $_POST['wf_token'];
        switch (@$_POST['do']) {
          case 'cashOut':
        	if ($setting->getValue('disable_payouts') == 1 || $setting->getValue('disable_manual_payouts') == 1) {
        	  $_SESSION['POPUP'][] = array('CONTENT' => 'Manual payouts are disabled.', 'TYPE' => 'info');
        	} else {
        	  $aBalance = $transaction->getBalance($_SESSION['USERDATA']['id']);
        	  $dBalance = $aBalance['confirmed'];
        	  if ($dBalance > $config['txfee']) {
        	    if (!$oPayout->isPayoutActive($_SESSION['USERDATA']['id'])) {
        	      if ($iPayoutId = $oPayout->createPayout($_SESSION['USERDATA']['id'], $wf_token)) {
        	        $wf_sent = 0;
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
        	if ($user->updateAccount($_SESSION['USERDATA']['id'], $_POST['paymentAddress'], $_POST['payoutThreshold'], $_POST['donatePercent'], $_POST['email'], $_POST['is_anonymous'], $ea_token)) {
        	  $ea_sent = 0;
        	  $_SESSION['POPUP'][] = array('CONTENT' => 'Account details updated', 'TYPE' => 'success');
        	} else {
        	  $_SESSION['POPUP'][] = array('CONTENT' => 'Failed to update your account: ' . $user->getError(), 'TYPE' => 'errormsg');
        	}
        	break;
        
          case 'updatePassword':
        	if ($user->updatePassword($_SESSION['USERDATA']['id'], $_POST['currentPassword'], $_POST['newPassword'], $_POST['newPassword2'], $cp_token)) {
        	  $cp_sent = 0;
        	  $_SESSION['POPUP'][] = array('CONTENT' => 'Password updated', 'TYPE' => 'success');
        	} else {
        	  $_SESSION['POPUP'][] = array('CONTENT' => $user->getError(), 'TYPE' => 'errormsg');
        	}
        	break;
        }
      }
    }
  }
}
// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
$smarty->assign("CHANGEPASSUNLOCKED", $cp_editable);
$smarty->assign("WITHDRAWUNLOCKED", $wf_editable);
$smarty->assign("DETAILSUNLOCKED", $ea_editable);
$smarty->assign("CHANGEPASSSENT", $cp_sent);
$smarty->assign("WITHDRAWSENT", $wf_sent);
$smarty->assign("DETAILSSENT", $ea_sent);
?>
