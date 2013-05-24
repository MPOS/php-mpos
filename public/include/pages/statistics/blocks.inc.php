<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');


// Grab the last blocks found
$iLimit = 30;
$aBlocksFoundData = $statistics->getBlocksFound($iLimit);
$aBlockData = $aBlocksFoundData[0];

// Propagate content our template
$smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
$smarty->assign("BLOCKLIMIT", $iLimit);

if ($_SESSION['AUTHENTICATED']) {
  $smarty->assign("CONTENT", "blocks_found.tpl");
} else {
  $smarty->assign("CONTENT", "default.tpl");
}
?>
