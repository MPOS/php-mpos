<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');
if (!$user->isAuthenticated()) header("Location: index.php?page=home");

// Grab the last blocks found
$iLimit = 30;
$aBlocksFoundData = $statistics->getBlocksFound($iLimit);
$aBlockData = $aBlocksFoundData[0];

// Propagate content our template
$smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
$smarty->assign("BLOCKLIMIT", $iLimit);

$smarty->assign("CONTENT", "default.tpl");
?>
