<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");

?>