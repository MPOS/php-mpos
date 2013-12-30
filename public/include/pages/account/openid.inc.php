<?php
// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');
    isset($_POST['token']) ? $token = $_POST['token'] : $token = '';
    if ($user->register(@$_POST['username'], @$_POST['password1'], @$_POST['password2'], @$_POST['pin'], @$_POST['email1'], @$_POST['email2'], $token)) {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Account information updated, please login');
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to create account: ' . $user->getError(), 'TYPE' => 'errormsg');
    }

// We load the default registration template instead of an action specific one
$smarty->assign("CONTENT", "default.tpl");
?>