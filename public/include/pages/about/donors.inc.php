<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($setting->getValue('disable_donors')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Donors are currently disabled. Please try again later.', 'TYPE' => 'errormsg');
  $smarty->assign("CONTENT", "disabled.tpl");
} else {
  $aDonors = $transaction->getDonations();

  // Tempalte specifics
  $smarty->assign("DONORS", $aDonors);
  $smarty->assign("CONTENT", "default.tpl");
}

?>
