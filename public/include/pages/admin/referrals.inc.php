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
empty($_REQUEST['start']) ? $start = 0 : $start = $_REQUEST['start'];
    
// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>