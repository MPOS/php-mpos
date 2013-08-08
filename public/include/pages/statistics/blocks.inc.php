<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Grab the last blocks found
if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  // Grab the last blocks found
  !empty($config['website']['blocks']['count']) ? $iLimit = $config['website']['blocks']['count'] : $iLimit = 20;
  $aBlocksFoundData = $statistics->getBlocksFound($iLimit);

  // Propagate content our template
  $smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
  $smarty->assign("BLOCKLIMIT", $iLimit);
} else {
  $debug->append('Using cached page', 3);
}

if ($config['website']['acl']['statistics']['blocks'] == 'public') {
  $smarty->assign("CONTENT", "default.tpl");
} else if ($user->isAuthenticated()) {
  $smarty->assign("CONTENT", "default.tpl");
}
?>
