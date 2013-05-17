<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Fetch data from litecoind
if ($bitcoin->can_connect() === true){
  if (!$dDifficulty = $memcache->get('dDifficulty')) {
    $dDifficulty = $bitcoin->query('getdifficulty');
    $memcache->set('dDifficulty', $dDifficulty, 60);
  }
  if (!$iBlock = $memcache->get('iBlock')) {
    $iBlock = $bitcoin->query('getblockcount');
    $memcache->set('iBlock', $iBlock, 60);
  }
} else {
  $iDifficulty = 1;
  $iBlock = 0;
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to pushpool service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
}

// Top share contributors
if (!$aContributorsShares = $memcache->get('aContributorsShares')) {
  $debug->append('STA Fetching contributor shares from database');
  $aContributorsShares = $statistics->getTopContributors('shares', 15);
  $memcache->set('aContributorsShares', $aContributorsShares, 60);
  $debug->append('END Fetching contributor shares from database');
}

// Top hash contributors
if (!$aContributorsHashes = $memcache->get('aContributorsHashes')) {
  $debug->append('STA Fetching contributor hashes from database');
  $aContributorsHashes = $statistics->getTopContributors('hashes', 15);
  $memcache->set('aContributorsHashes', $aContributorsHashes, 60);
  $debug->append('END Fetching contributor hashes from database');
}

// Grab the last 10 blocks found
$aBlocksFoundData = $statistics->getBlocksFound(10);
$aBlockData = $aBlocksFoundData[0];

// Estimated time to find the next block
if (!$iCurrentPoolHashrate = $memcache->get('iCurrentPoolHashrate')) {
  $debug->append('Fetching iCurrentPoolHashrate from database');
  $iCurrentPoolHashrate =  $statistics->getCurrentHashrate();
  $memcache->set('iCurrentPoolHashrate', $iCurrentPoolHashrate, 60);
}
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
$smarty->assign("CONTRIBSHARES", $aContributorsShares);
$smarty->assign("CONTRIBHASHES", $aContributorsHashes);
$smarty->assign("CURRENTBLOCK", $iBlock);
$smarty->assign("LASTBLOCK", $aBlockData['height']);
$smarty->assign("DIFFICULTY", $dDifficulty);
$smarty->assign("REWARD", $config['reward']);

if ($_SESSION['AUTHENTICATED']) {
  $smarty->assign("CONTENT", "authenticated.tpl");
} else {
  $smarty->assign("CONTENT", "default.tpl");
}
?>
