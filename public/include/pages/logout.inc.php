<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// This probably (?) never fails
$user->logoutUser();
$smarty->assign("CONTENT", "default.tpl");
// header('Location: index.php?page=home');
?>
