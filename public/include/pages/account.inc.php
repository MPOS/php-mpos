<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

if (!$_SESSION['AUTHENTICATED']) {
  header('Location: index.php?page=home');
}

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
