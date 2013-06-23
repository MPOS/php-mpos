<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

$aDonors = $transaction->getDonations();

// Tempalte specifics
$smarty->assign("DONORS", $aDonors);
$smarty->assign("CONTENT", "default.tpl");
?>
