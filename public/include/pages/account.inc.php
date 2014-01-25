<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($user->isAuthenticated()) {
  // Tempalte specifics
  $smarty->assign("CONTENT", "default.tpl");
}
?>
