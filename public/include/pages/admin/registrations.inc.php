<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

// Some defaults
$iLimit = 10;
$smarty->assign('LIMIT', $iLimit);
empty($_REQUEST['registeredstart']) ? $registeredstart = 0 : $registeredstart = $_REQUEST['registeredstart'];

// get last 10 Registrations
$aLastRegisteredUsers = $user->getLastRegisteredUsers($iLimit, $registeredstart);
$smarty->assign("LASTREGISTEREDUSERS", $aLastRegisteredUsers);

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
