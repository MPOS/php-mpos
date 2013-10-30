<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');


// passing config settings
$smarty->assign("SITECOINNAME", $config['gettingstarted']['coinname']);
$smarty->assign("SITECOINURL", $config['gettingstarted']['coinurl']);
$smarty->assign("SITESTRATUMPORT", $config['gettingstarted']['stratumport']);

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
