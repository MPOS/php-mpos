<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

$smarty->assign("CONTENT", "default.tpl");
?>
