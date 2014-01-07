<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');
$smarty->assign("CONTENT", "default.tpl");
?>
