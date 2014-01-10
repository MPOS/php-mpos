<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

// Some defaults
$iLimit = 30;
$smarty->assign('LIMIT', $iLimit);
empty($_REQUEST['start']) ? $start = 0 : $start = $_REQUEST['start'];
empty($_REQUEST['order']) ? $order = 'username' : $order = $_REQUEST['order'];
$smarty->assign('SORTING', array('username' => 'Username', 'confirmed' => 'Balance'));
$smarty->assign('ADMIN', array('' => '', '0' => 'No', '1' => 'Yes'));
$smarty->assign('LOCKED', array('' => '', '0' => 'No', '1' => 'Yes'));
$smarty->assign('NOFEE', array('' => '', '0' => 'No', '1' => 'Yes'));

// Catch our JS queries to update some settings
switch (@$_REQUEST['do']) {
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

// Gernerate the GET URL for filters
if (isset($_REQUEST['filter'])) {
  // Create filter URL for pagination arrows
  $strFilters = '';
  foreach (@$_REQUEST['filter'] as $filter => $value) {
    $filter = "filter[$filter]";
    $strFilters .= "&$filter=$value";
  }
  $smarty->assign('FILTERS', $strFilters);

  // Fetch requested users
  if ($aUsers = $statistics->getAllUserStats($_REQUEST['filter'], $iLimit, $start, $order)) {
    // Assign our variables
    $smarty->assign("USERS", $aUsers);
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => 'Could not find any users', 'TYPE' => 'errormsg');
  }
}


// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
