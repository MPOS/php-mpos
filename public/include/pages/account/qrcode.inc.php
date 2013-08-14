<?php
// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($user->isAuthenticated()) $smarty->assign("CONTENT", "default.tpl");
?>
