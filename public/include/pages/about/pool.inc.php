<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($setting->getValue('disable_about')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Donors are currently disabled. Please try again later.', 'TYPE' => 'errormsg');
  $smarty->assign("CONTENT", "disabled.tpl");
} else {
  // Tempalte specifics
  $smarty->assign("CONTENT", "default.tpl");
}

?>
