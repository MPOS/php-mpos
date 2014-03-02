<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

$smarty->assign("SITESTRATUMURL", $config['gettingstarted']['stratumurl']);
$smarty->assign("SITESTRATUMPORT", $config['gettingstarted']['stratumport']);
$smarty->assign("SITECOINNAME", $config['gettingstarted']['coinname']);
$smarty->assign("SITECOINURL", $config['gettingstarted']['coinurl']);

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
