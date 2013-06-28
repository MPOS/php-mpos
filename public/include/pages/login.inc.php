<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

if ( $user->checkLogin($_POST['username'],$_POST['password']) ) {
  header('Location: index.php?page=home');
} else if (@$_POST['username'] && @$_POST['password']) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to login: '. $user->getError(), 'TYPE' => 'errormsg');
}

$smarty->assign('CONTENT', 'default.tpl');
?>
