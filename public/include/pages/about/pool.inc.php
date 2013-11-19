<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

if ($setting->getValue('disable_about')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Donors are currently disabled. Please try again later.', 'TYPE' => 'errormsg');
  $smarty->assign("CONTENT", "disabled.tpl");
} else {
  // Tempalte specifics
  $smarty->assign("CONTENT", "default.tpl");
}

?>
