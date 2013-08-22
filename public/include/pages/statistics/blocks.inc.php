<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Grab the last blocks found
if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  // Grab the last blocks found
  $setting->getValue('statistics_block_count') ? $iLimit = $setting->getValue('statistics_block_count') : $iLimit = 20;
  $aBlocksFoundData = $statistics->getBlocksFound($iLimit);

  // Propagate content our template
  $smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
  $smarty->assign("BLOCKLIMIT", $iLimit);
} else {
  $debug->append('Using cached page', 3);
}

if ($setting->getValue('acl_block_statistics')) {
  $smarty->assign("CONTENT", "default.tpl");
} else if ($user->isAuthenticated()) {
  $smarty->assign("CONTENT", "default.tpl");
}
?>
