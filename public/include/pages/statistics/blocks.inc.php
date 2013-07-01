<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');
if (!$user->isAuthenticated()) header("Location: index.php?page=home");

// Grab the last blocks found
empty($_REQUEST['limit']) ? $iLimit = 20 : $iLimit = $_REQUEST['limit'];
$aBlocksFoundData = $statistics->getBlocksFound($iLimit);

// Propagate content our template
$smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
$smarty->assign("BLOCKLIMIT", $iLimit);

$smarty->assign("CONTENT", "default.tpl");
?>
