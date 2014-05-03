<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

// Some defaults
$interval = 600;
$iActiveWorkers = $worker->getCountAllActiveWorkers();
$iActiveWorkers > 30 ? $iLimit = 30 : $iLimit = $iActiveWorkers;
empty($_REQUEST['start']) ? $start = 0 : $start = $_REQUEST['start'];

$aWorkers = $worker->getAllWorkers($iLimit, $interval, $start);

$smarty->assign('DISABLE_IDLEWORKERNOTIFICATIONS', $setting->getValue('notifications_disable_idle_worker'));
$smarty->assign('LIMIT', $iLimit);
$smarty->assign('WORKERS', $aWorkers);
$smarty->assign('CONTENT', 'default.tpl');

?>
