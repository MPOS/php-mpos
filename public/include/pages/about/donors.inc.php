<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// ACL check
switch($setting->getValue('acl_donors_page', 1)) {
case '0':
  if ($user->isAuthenticated()) {
    $aDonors = $transaction->getDonations();
    $smarty->assign("DONORS", $aDonors);
    $smarty->assign("CONTENT", "default.tpl");
  }
  break;
case '1':
  $aDonors = $transaction->getDonations();
  $smarty->assign("DONORS", $aDonors);
  $smarty->assign("CONTENT", "default.tpl");
  break;
case '2':
  $_SESSION['POPUP'][] = array('CONTENT' => 'Page currently disabled. Please try again later.', 'TYPE' => 'alert alert-danger');
  $smarty->assign("CONTENT", "disabled.tpl");
  break;
}
