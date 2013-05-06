<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');


if ($user->register($_POST['username'], $_POST['password1'], $_POST['password2'], $_POST['pin'], $_POST['email1'], $_POST['email2'])) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Account created, please login');
} else {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to create account: ' . $user->error, 'TYPE' => 'errormsg');
}

// We load the default registration template instead of an action specific one
$smarty->assign("CONTENT", "../default.tpl");
?>
