<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

<<<<<<< HEAD


=======
$smarty->assign("SITESTRATUMPORT", $config['gettingstarted']['stratumport']);
$smarty->assign("SITECOINNAME", $config['gettingstarted']['coinname']);
$smarty->assign("SITECOINURL", $config['gettingstarted']['coinurl']);
>>>>>>> upstream/next

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
