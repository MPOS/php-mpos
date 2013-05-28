<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Check user to ensure they are admin
if (!$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die();
}

if ($_POST['query']) {
  // Fetch all users from DB cross referencing all stats
}

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
