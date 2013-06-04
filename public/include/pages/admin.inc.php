<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die();
}

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
