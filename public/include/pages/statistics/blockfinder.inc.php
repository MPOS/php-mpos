<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Grab Block Finder
if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  $getBlocksSolvedbyAccount = $statistics->getBlocksSolvedbyAccount();
  $smarty->assign("BLOCKSSOLVEDBYACCOUNT", $getBlocksSolvedbyAccount);
  
  if(isset($_SESSION['USERDATA']['id'])){
    $getBlocksSolvedbyWorker = $statistics->getBlocksSolvedbyWorker($_SESSION['USERDATA']['id']);
    $smarty->assign("BLOCKSSOLVEDBYWORKER", $getBlocksSolvedbyWorker);
  }

} else {
  $debug->append('Using cached page', 3);
}

// Public / private page detection
if ($setting->getValue('acl_blockfinder_statistics')) {
  $smarty->assign("CONTENT", "finder.tpl");
} else if ($user->isAuthenticated()) {
  $smarty->assign("CONTENT", "finder.tpl");
} else {
  $smarty->assign("CONTENT", "default.tpl");
}
?>
