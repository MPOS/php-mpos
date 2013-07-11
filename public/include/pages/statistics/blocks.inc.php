<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Grab the last blocks found
$iLimit = 20;
$aBlocksFoundData = $statistics->getBlocksFound($iLimit);

// Propagate content our template
$smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
$smarty->assign("BLOCKLIMIT", $iLimit);

if ($config['website']['acl']['statistics']['blocks'] == 'public') {
  $smarty->assign("CONTENT", "default.tpl");
} else if ($user->isAuthenticated()) {
  $smarty->assign("CONTENT", "default.tpl");
}
?>
