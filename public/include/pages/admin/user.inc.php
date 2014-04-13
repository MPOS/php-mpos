<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

// Some defaults
$iLimit = 100;
$smarty->assign('LIMIT', $iLimit);
empty($_REQUEST['start']) ? $start = 0 : $start = $_REQUEST['start'];
$smarty->assign('ADMIN', array('' => '', '0' => 'No', '1' => 'Yes'));
$smarty->assign('LOCKED', array('' => '', '0' => 'No', '1' => 'Yes'));
$smarty->assign('NOFEE', array('' => '', '0' => 'No', '1' => 'Yes'));

// Catch our JS queries to update some settings
switch (@$_REQUEST['do']) {
case 'lock':
  $supress_master = 1;
  // Reset user account
  if ($user->isLocked($_POST['account_id']) == 0) {
    $user->setLocked($_POST['account_id'], 2);
  } else {
    $user->setLocked($_POST['account_id'], 0);
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

// Gernerate the GET URL for filters
if (isset($_REQUEST['filter'])) {
  // Fetch round shares for estimates
  $aRoundShares = $statistics->getRoundShares();

  // Create filter URL for pagination arrows
  $strFilters = '';
  foreach (@$_REQUEST['filter'] as $filter => $value) {
    $filter = "filter[$filter]";
    $strFilters .= "&$filter=$value";
  }
  $smarty->assign('FILTERS', $strFilters);

  // Fetch requested users
  if ($aUsers = $statistics->getAllUserStats($_REQUEST['filter'], $iLimit, $start)) {
    // Add additional stats to each user
    foreach ($aUsers as $iKey => $aUser) {
      $aBalance = $transaction->getBalance($aUser['id']);
      $aUser['balance'] = $aBalance['confirmed'];
      $aUser['signup_timestamp'] = $user->getSignupTime($aUser['id']);
      $aUserMiningStats = $statistics->getUserMiningStats($aUser['username'], $aUser['id']);
      $aUser['hashrate'] = $aUserMiningStats['hashrate'];

      if ($config['payout_system'] == 'pps') {
        $aUser['sharerate'] = $aUserMiningStats['sharerate'];
        $aUser['difficulty'] = $aUserMiningStats['avgsharediff'];
        $aUser['estimates'] = $statistics->getUserEstimates($aUser['sharerate'], $aUser['difficulty'], $user->getUserDonatePercent($aUser['id']), $user->getUserNoFee($aUser['id']), $statistics->getPPSValue());
      } else {
        $aUser['estimates'] = $statistics->getUserEstimates($aRoundShares, $aUser['shares'], $aUser['donate_percent'], $aUser['no_fees']);
      }
      $aUsers[$iKey] = $aUser;
    }

    // Assign our variables
    $smarty->assign("USERS", $aUsers);
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Could not find any users', 'TYPE' => 'alert alert-danger');
  }
}


// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
