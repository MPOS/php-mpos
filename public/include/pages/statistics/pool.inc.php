<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Fetch data from wallet
if ($bitcoin->can_connect() === true){
  $dDifficulty = $bitcoin->getdifficulty();
  if (is_array($dDifficulty) && array_key_exists('proof-of-work', $dDifficulty))
    $dDifficulty = $dDifficulty['proof-of-work'];
  $iBlock = $bitcoin->getblockcount();
} else {
  $dDifficulty = 1;
  $iBlock = 0;
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to wallet RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
}

// Top share contributors
$aContributorsShares = $statistics->getTopContributors('shares', 15);

// Top hash contributors
$aContributorsHashes = $statistics->getTopContributors('hashes', 15);

// Grab the last 10 blocks found
$iLimit = 5;
$aBlocksFoundData = $statistics->getBlocksFound($iLimit);
count($aBlocksFoundData) > 0 ? $aBlockData = $aBlocksFoundData[0] : $aBlockData = array();

// Estimated time to find the next block
$iCurrentPoolHashrate =  $statistics->getCurrentHashrate();
$iCurrentPoolHashrate == 0 ? $iCurrentPoolHashrate = 1 : true;

// Time in seconds, not hours, using modifier in smarty to translate
$iEstTime = $dDifficulty * pow(2,32) / ($iCurrentPoolHashrate * 1000);

// Time since last block
$now = new DateTime( "now" );
if (!empty($aBlockData)) {
  $dTimeSinceLast = ($now->getTimestamp() - $aBlockData['time']);
} else {
  $dTimeSinceLast = 0;
}

// Propagate content our template
$smarty->assign("ESTTIME", $iEstTime);
$smarty->assign("TIMESINCELAST", $dTimeSinceLast);
$smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
$smarty->assign("BLOCKLIMIT", $iLimit);
$smarty->assign("CONTRIBSHARES", $aContributorsShares);
$smarty->assign("CONTRIBHASHES", $aContributorsHashes);
$smarty->assign("CURRENTBLOCK", $iBlock);
count($aBlockData) > 0 ? $smarty->assign("LASTBLOCK", $aBlockData['height']) : $smarty->assign("LASTBLOCK", 0);
$smarty->assign("DIFFICULTY", $dDifficulty);
$smarty->assign("REWARD", $config['reward']);

if ($user->isAuthenticated()) {
  $smarty->assign("CONTENT", "authenticated.tpl");
} else {
  $smarty->assign("CONTENT", "../default.tpl");
}
?>
