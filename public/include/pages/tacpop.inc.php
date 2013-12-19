<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');
$smarty->display('tac/default.tpl');
$supress_master = 1;
?>
