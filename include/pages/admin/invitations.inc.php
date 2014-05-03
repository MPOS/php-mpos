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
empty($_REQUEST['invitersstart']) ? $invitersstart = 0 : $invitersstart = $_REQUEST['invitersstart'];

// Fetching invitation Informations
if (!$setting->getValue('disable_invitations')) {
  // get last 10 Inviters
  $aTopInviters = $user->getTopInviters($iLimit, $invitersstart);
  $smarty->assign("TOPINVITERS", $aTopInviters);
}

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
