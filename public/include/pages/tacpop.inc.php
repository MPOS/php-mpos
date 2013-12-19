<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

$smarty->display('default.tpl');
$supress_master = 1;
?>
