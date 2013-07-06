<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');
if (!$user->isAuthenticated()) header("Location: index.php?page=home");

// Grab the last blocks found
$iLimit = 20;
$aBlocksFoundData = $statistics->getBlocksFound($iLimit);

// Propagate content our template
$smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
$smarty->assign("BLOCKLIMIT", $iLimit);

$smarty->assign("CONTENT", "default.tpl");
?>
