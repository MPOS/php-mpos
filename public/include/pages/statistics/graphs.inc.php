<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  if ($user->isAuthenticated()) {
    $aHourlyHashRates = $statistics->getHourlyHashrateByAccount($_SESSION['USERDATA']['username'], $_SESSION['USERDATA']['id']);
    $aPoolHourlyHashRates = $statistics->getHourlyHashrateByPool();
  }
  $smarty->assign("YOURHASHRATES", @$aHourlyHashRates);
  $smarty->assign("POOLHASHRATES", @$aPoolHourlyHashRates);
} else {
  $debug->append('Using cached page', 3);
}

$smarty->assign("CONTENT", "default.tpl");
?>
