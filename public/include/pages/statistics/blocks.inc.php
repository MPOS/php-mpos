<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');
if (!$user->isAuthenticated()) header("Location: index.php?page=home");

// Grab the last blocks found
if (!$smarty->isCached('master.tpl', md5(serialize($_REQUEST)))) {
  $debug->append('No cached version available, fetching from backend', 3);
  // Grab the last blocks found
  $iLimit = 20;
  $aBlocksFoundData = $statistics->getBlocksFound($iLimit);

  // Propagate content our template
  $smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
  $smarty->assign("BLOCKLIMIT", $iLimit);

} else {
  $debug->append('Using cached page', 3);
}

$smarty->assign("CONTENT", "default.tpl");
?>
