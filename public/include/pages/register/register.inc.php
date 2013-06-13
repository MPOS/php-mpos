<?php
// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if (!$config['website']['registration']) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Account registration is currently disabled. Please try again later.', 'TYPE' => 'errormsg');
} else if ($user->register($_POST['username'], $_POST['password1'], $_POST['password2'], $_POST['pin'], $_POST['email1'], $_POST['email2']) && $config['website']['registration']) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Account created, please login');
} else {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to create account: ' . $user->getError(), 'TYPE' => 'errormsg');
}

// We load the default registration template instead of an action specific one
$smarty->assign("CONTENT", "../default.tpl");
?>
