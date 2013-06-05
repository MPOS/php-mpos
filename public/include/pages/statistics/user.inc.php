<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

$aHourlyHashRates = $statistics->getHourlyHashrateByAccount($_SESSION['USERDATA']['id']);

// Propagate content our template
$smarty->assign("YOURHASHRATES", $aHourlyHashRates);
$smarty->assign("DIFFICULTY", $dDifficulty);

if ($_SESSION['AUTHENTICATED']) {
  $smarty->assign("CONTENT", "default.tpl");
}
?>
