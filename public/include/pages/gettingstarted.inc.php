<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

$smarty->assign("SITESTRATUMPORT", $config['gettingstarted']['coinname']);
$smarty->assign("SITECOINNAME", $config['gettingstarted']['coinname']);
$smarty->assign("SITECOINURL", $config['gettingstarted']['coinurl']);

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
