<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Grab Block Finder
if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);

  $getBlocksSolvedbyAccount = $statistics->getBlocksSolvedbyAccount();
  $smarty->assign("BLOCKSSOLVEDBYACCOUNT", $getBlocksSolvedbyAccount);
} else {
  $debug->append('Using cached page', 3);
}

if ($setting->getValue('acl_blockfinder_statistics')) {
  $smarty->assign("CONTENT", "finder.tpl");
} else if ($user->isAuthenticated()) {
  $getBlocksSolvedbyWorker = $statistics->getBlocksSolvedbyWorker($_SESSION['USERDATA']['id']);
  $smarty->assign("BLOCKSSOLVEDBYWORKER", $getBlocksSolvedbyWorker);
  $smarty->assign("CONTENT", "finder.tpl");
} else {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Block Finders are currently disabled. Please try again later.', 'TYPE' => 'errormsg');
  $smarty->assign("CONTENT", "default.tpl");
}
?>
