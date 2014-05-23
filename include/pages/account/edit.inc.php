<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// twofactor stuff
$cp_editable = $wf_editable = $ea_editable = $wf_sent = $ea_sent = $cp_sent = 0;

// 2fa - set old token so we can use it if an error happens or we need to use post
$oldtoken_ea = (isset($_POST['ea_token']) && $_POST['ea_token'] !== '') ? $_POST['ea_token'] : @$_GET['ea_token'];
$oldtoken_cp = (isset($_POST['cp_token']) && $_POST['cp_token'] !== '') ? $_POST['cp_token'] : @$_GET['cp_token'];
$oldtoken_wf = (isset($_POST['wf_token']) && $_POST['wf_token'] !== '') ? $_POST['wf_token'] : @$_GET['wf_token'];
$updating = (@$_POST['do']) ? 1 : 0;

if ($user->isAuthenticated()) {
  if ($config['twofactor']['enabled']) {
    if ($config['twofactor']['options']['details'] OR $config['twofactor']['options']['changepw'] OR $config['twofactor']['options']['withdraw']) {
      $popupmsg = 'E-mail confirmations are required for ';
      $popuptypes = array();
      if ($config['twofactor']['options']['details'] && $oldtoken_ea !== "") {
        $popuptypes[] = 'editing your details';
        $ea_editable = $user->token->isTokenValid($_SESSION['USERDATA']['id'], $oldtoken_ea, 5);
        $ea_sent = $user->token->doesTokenExist('account_edit', $_SESSION['USERDATA']['id']);
      }
      if ($config['twofactor']['options']['changepw'] && $oldtoken_cp !== "") {
        $popuptypes[] = 'changing your password';
        $cp_editable = $user->token->isTokenValid($_SESSION['USERDATA']['id'], $oldtoken_cp, 6);
        $cp_sent = $user->token->doesTokenExist('change_pw', $_SESSION['USERDATA']['id']);
      }
      if ($config['twofactor']['options']['withdraw'] && $oldtoken_wf !== "") {
        $popuptypes[] = 'withdrawals';
        $wf_editable = $user->token->isTokenValid($_SESSION['USERDATA']['id'], $oldtoken_wf, 7);
        $wf_sent = $user->token->doesTokenExist('withdraw_funds', $_SESSION['USERDATA']['id']);
      }

      // get the status of a token if set
      $message_tokensent_invalid = 'A token was sent to your e-mail that will allow you to ';
      $message_tokensent_valid = 'You can currently ';
      $messages_tokensent_status = array(
        'ea' => 'edit your account details',
        'wf' => 'withdraw funds',
        'cp' => 'change your password'
      );
      // build the message we're going to show them for their token(s)
      $eaprep_sent = ($ea_sent) ? $message_tokensent_valid.$messages_tokensent_status['ea'] : "";
      $eaprep_edit = ($ea_editable) ? $message_tokensent_invalid.$messages_tokensent_status['ea'] : "";
      $wfprep_sent = ($wf_sent) ? $message_tokensent_valid.$messages_tokensent_status['wf'] : "";
      $wfprep_edit = ($wf_editable) ? $message_tokensent_invalid.$messages_tokensent_status['wf'] : "";
      $cpprep_sent = ($cp_sent) ? $message_tokensent_valid.$messages_tokensent_status['cp'] : "";
      $cpprep_edit = ($cp_editable) ? $message_tokensent_invalid.$messages_tokensent_status['cp'] : "";
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
      // display global notice about tokens being in use and for which bits they're active
      $_SESSION['POPUP'][] = array('CONTENT' => $popupmsg, 'TYPE' => 'alert alert-warning');
    }
  }

  if (isset($_POST['do']) && $_POST['do'] == 'genPin') {
    if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
      if ($user->generatePin($_SESSION['USERDATA']['id'], $_POST['currentPassword'])) {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Your PIN # has been sent to your email.', 'TYPE' => 'alert alert-success');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => $user->getError(), 'TYPE' => 'alert alert-danger');
      }
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
    }
  }
  else {
    if ( @$_POST['do'] && !$user->checkPin($_SESSION['USERDATA']['id'], @$_POST['authPin'])) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Invalid PIN. ' . ($config['maxfailed']['pin'] - $user->getUserPinFailed($_SESSION['USERDATA']['id'])) . ' attempts remaining.', 'TYPE' => 'alert alert-danger');
    } else {
      if (isset($_POST['unlock']) && isset($_POST['utype'])) {
        $validtypes = array('account_edit','change_pw','withdraw_funds');
        $isvalid = in_array($_POST['utype'],$validtypes);
        if ($isvalid) {
          $ctype = strip_tags($_POST['utype']);
          if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
            $send = $user->sendChangeConfigEmail($ctype, $_SESSION['USERDATA']['id']);
            if ($send) {
              $_SESSION['POPUP'][] = array('CONTENT' => 'A confirmation was sent to your e-mail, follow that link to continue', 'TYPE' => 'alert alert-success');
            } else {
              $_SESSION['POPUP'][] = array('CONTENT' => $user->getError(), 'TYPE' => 'alert alert-danger');
            }
          } else {
            $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
          }
        }
      } else {
        switch (@$_POST['do']) {
          case 'cashOut':
        	$aBalance = $transaction->getBalance($_SESSION['USERDATA']['id']);
        	$dBalance = $aBalance['confirmed'];
        	if ($setting->getValue('disable_payouts') == 1 || $setting->getValue('disable_manual_payouts') == 1) {
        	  $_SESSION['POPUP'][] = array('CONTENT' => 'Manual payouts are disabled.', 'TYPE' => 'alert alert-warning');
          } else if ($config['twofactor']['enabled'] && $config['twofactor']['options']['withdraw'] && !$wf_editable) {
            $_SESSION['POPUP'][] = array('CONTENT' => 'You have not yet unlocked account withdrawls.', 'TYPE' => 'alert alert-danger');
          } else if ($aBalance['confirmed'] < $config['mp_threshold']) {
            $_SESSION['POPUP'][] = array('CONTENT' => 'Payout must be greater or equal than ' . $config['mp_threshold'] . '.', 'TYPE' => 'info');
          } else if (!$user->getCoinAddress($_SESSION['USERDATA']['id'])) {
            $_SESSION['POPUP'][] = array('CONTENT' => 'You have no payout address set.', 'TYPE' => 'alert alert-danger');
        	} else {
        	  $user->log->log("info", $_SESSION['USERDATA']['username']." requesting manual payout");
        	  if ($dBalance > $config['txfee_manual']) {
        	    if (!$oPayout->isPayoutActive($_SESSION['USERDATA']['id'])) {
        	      if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
        	        if ($iPayoutId = $oPayout->createPayout($_SESSION['USERDATA']['id'], $oldtoken_wf)) {
        	          $_SESSION['POPUP'][] = array('CONTENT' => 'Created new manual payout request with ID #' . $iPayoutId);
        	        } else {
        	          $_SESSION['POPUP'][] = array('CONTENT' => $iPayoutId->getError(), 'TYPE' => 'alert alert-danger');
        	        }
        	      } else {
        	        $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
        	      }
        	    } else {
        	      $_SESSION['POPUP'][] = array('CONTENT' => 'You already have one active manual payout request.', 'TYPE' => 'alert alert-danger');
        	    }
        	  } else {
        	    $_SESSION['POPUP'][] = array('CONTENT' => 'Insufficient funds, you need more than ' . $config['txfee_manual'] . ' ' . $config['currency'] . ' to cover transaction fees', 'TYPE' => 'alert alert-danger');
        	  }
        	}
        	break;

          case 'updateAccount':
            if ($config['twofactor']['enabled'] && $config['twofactor']['options']['details'] && !$ea_editable) {
              $_SESSION['POPUP'][] = array('CONTENT' => 'You have not yet unlocked account updates.', 'TYPE' => 'alert alert-danger');
            } else if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
              if ($user->updateAccount($_SESSION['USERDATA']['id'], $_POST['paymentAddress'], $_POST['payoutThreshold'], $_POST['donatePercent'], $_POST['email'], $_POST['timezone'], $_POST['is_anonymous'], $oldtoken_ea)) {
                $_SESSION['USERDATA']['timezone'] = $_POST['timezone'];
              	$_SESSION['POPUP'][] = array('CONTENT' => 'Account details updated', 'TYPE' => 'alert alert-success');
              } else {
              	$_SESSION['POPUP'][] = array('CONTENT' => 'Failed to update your account: ' . $user->getError(), 'TYPE' => 'alert alert-danger');
              }
            } else {
              $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
            }
        	break;

          case 'updatePassword':
            if ($config['twofactor']['enabled'] && $config['twofactor']['options']['changepw'] && !$cp_editable) {
              $_SESSION['POPUP'][] = array('CONTENT' => 'You have not yet unlocked password updates.', 'TYPE' => 'alert alert-danger');
            } else if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
              if ($user->updatePassword($_SESSION['USERDATA']['id'], $_POST['currentPassword'], $_POST['newPassword'], $_POST['newPassword2'], $oldtoken_cp)) {
                $_SESSION['POPUP'][] = array('CONTENT' => 'Password updated', 'TYPE' => 'alert alert-success');
              } else {
                $_SESSION['POPUP'][] = array('CONTENT' => $user->getError(), 'TYPE' => 'alert alert-danger');
              }
            } else {
              $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
            }
        	break;
        }
      }
    }
  }
}


// 2fa - one last time so we can sync with changes we made during this page
if ($config['twofactor']['enabled'] && $user->isAuthenticated()) {
  // set the token to be the old token, just in case an error occured
  $ea_token = (@$oldtoken_ea !== '') ? $oldtoken_ea : @$ea_token;
  $wf_token = (@$oldtoken_wf !== '') ? $oldtoken_wf : @$wf_token;
  $cp_token = (@$oldtoken_cp !== '') ? $oldtoken_cp : @$cp_token;
  if ($config['twofactor']['options']['details'] && $ea_token !== "") {
    $ea_editable = $user->token->isTokenValid($_SESSION['USERDATA']['id'], $ea_token, 5);
    $ea_sent = $user->token->doesTokenExist('account_edit', $_SESSION['USERDATA']['id']);
  }
  if ($config['twofactor']['options']['changepw'] && $cp_token !== "") {
    $cp_editable = $user->token->isTokenValid($_SESSION['USERDATA']['id'], $cp_token, 6);
    $cp_sent = $user->token->doesTokenExist('change_pw', $_SESSION['USERDATA']['id']);
  }
  if ($config['twofactor']['options']['withdraw'] && $wf_token !== "") {
    $wf_editable = $user->token->isTokenValid($_SESSION['USERDATA']['id'], $wf_token, 7);
    $wf_sent = $user->token->doesTokenExist('withdraw_funds', $_SESSION['USERDATA']['id']);
  }
  
  // display token info per each - only when sent and editable or just sent, not by default
  (!empty($eaprep_sent) && !empty($eaprep_edit)) ? $_SESSION['POPUP'][] = array('CONTENT' => $eaprep_sent, 'TYPE' => 'alert alert-success'):"";
  (!empty($eaprep_sent) && empty($eaprep_edit)) ? $_SESSION['POPUP'][] = array('CONTENT' => $message_tokensent_invalid.$messages_tokensent_status['ea'], 'TYPE' => 'alert alert-success'):"";
  (!empty($wfprep_sent) && !empty($wfprep_edit)) ? $_SESSION['POPUP'][] = array('CONTENT' => $wfprep_sent, 'TYPE' => 'alert alert-success'):"";
  (!empty($wfprep_sent) && empty($wfprep_edit)) ? $_SESSION['POPUP'][] = array('CONTENT' => $message_tokensent_invalid.$messages_tokensent_status['wf'], 'TYPE' => 'alert alert-success'):"";
  (!empty($cpprep_sent) && !empty($cpprep_edit)) ? $_SESSION['POPUP'][] = array('CONTENT' => $cpprep_sent, 'TYPE' => 'alert alert-success'):"";
  (!empty($cpprep_sent) && empty($cpprep_edit)) ? $_SESSION['POPUP'][] = array('CONTENT' => $message_tokensent_invalid.$messages_tokensent_status['cp'], 'TYPE' => 'alert alert-success'):"";
  // two-factor stuff
  $smarty->assign("CHANGEPASSUNLOCKED", $cp_editable);
  $smarty->assign("WITHDRAWUNLOCKED", $wf_editable);
  $smarty->assign("DETAILSUNLOCKED", $ea_editable);
  $smarty->assign("CHANGEPASSSENT", $cp_sent);
  $smarty->assign("WITHDRAWSENT", $wf_sent);
  $smarty->assign("DETAILSSENT", $ea_sent);
}

// Grab our timezones
$smarty->assign('TIMEZONES', DateTimeZone::listIdentifiers());

// Fetch donation threshold
$smarty->assign("DONATE_THRESHOLD", $config['donate_threshold']);

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
