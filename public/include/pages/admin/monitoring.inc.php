<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

// Load our cron list $aMonitorCrons
require_once(INCLUDE_DIR . '/config/monitor_crons.inc.php');

// Data array for template
foreach ($aMonitorCrons as $strCron) {
  $aCronStatus[$strCron] = array(
    'disabled' => $monitoring->getStatus($strCron . '_disabled'),
    'exit' => $monitoring->getStatus($strCron . '_status'),
    'active' => $monitoring->getStatus($strCron . '_active'),
    'runtime' => $monitoring->getStatus($strCron . '_runtime'),
    'starttime' => $monitoring->getStatus($strCron . '_starttime'),
    'endtime' => $monitoring->getStatus($strCron . '_endtime'),
    'message' => $monitoring->getStatus($strCron . '_message'),
  );
}
$smarty->assign("CRONSTATUS", $aCronStatus);

// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
