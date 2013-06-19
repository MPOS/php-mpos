<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($user->isAuthenticated()) {
  // Tempalte specifics
  $smarty->assign("CONTENT", "default.tpl");
}
?>
