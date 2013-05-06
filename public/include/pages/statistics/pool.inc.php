<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// Fetch data from litecoind
if ($bitcoin->can_connect() === true){
  $dDifficulty = $bitcoin->query('getdifficulty');
  $iBlock = $bitcoin->query('getblockcount');
} else {
  $iDifficulty = 1;
  $iBlock = 0;
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to pushpool service: ' . $bitcoin->can_connect(), 'TYPE' => 'errormsg');
}

// Top 15 hashrate list
$stmt = $mysqli->prepare("SELECT username, id, hashrate FROM webUsers WHERE hashrate != '0' ORDER BY hashrate DESC LIMIT 15");
$stmt->execute();
$hashrates= $stmt->get_result();
$aHashData = $hashrates->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Top 15 Contributors
# SELECT id, shares_this_round AS shares FROM webUsers WHERE shares_this_round > 0 ORDER BY shares DESC LIMIT
$stmt = $mysqli->prepare("SELECT id, shares_this_round AS shares, username FROM webUsers WHERE shares_this_round > 0 ORDER BY shares DESC LIMIT 15");
$stmt->execute();
$contributors = $stmt->get_result();
$aContributorData = $contributors->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Grab the last block found
$stmt = $mysqli->prepare("SELECT n.blockNumber, n.confirms, n.timestamp FROM winning_shares w, networkBlocks n WHERE w.blockNumber = n.blockNumber ORDER BY w.blockNumber DESC LIMIT 1");
$stmt->execute();
$blocks = $stmt->get_result();
$aBlockData = $blocks->fetch_array();
$stmt->close();

// Grab the last 10 blocks found
$stmt = $mysqli->prepare("SELECT DISTINCT w.shareCount AS shares, w.username, n.blockNumber, n.confirms, n.timestamp FROM winning_shares w, networkBlocks n WHERE w.blockNumber = n.blockNumber ORDER BY w.blockNumber DESC LIMIT 10");
$stmt->execute();
$blocksfound = $stmt->get_result();
$aBlocksFoundData = $blocksfound->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Estimated time to find the next block
$iEstTime = (($dDifficulty * bcpow(2,$config['difficulty'])) / ( $settings->getValue('currenthashrate') * 1000));
$now = new DateTime( "now" );
$dTimeSinceLast = ($now->getTimestamp() - $aBlockData['timestamp']);

// Propagate content our template
$smarty->assign("ESTTIME", $iEstTime);
$smarty->assign("TIMESINCELAST", $dTimeSinceLast);
$smarty->assign("CONTRIBUTORS", $aContributorData);
$smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
$smarty->assign("TOPHASHRATES", $aHashData);
$smarty->assign("CURRENTBLOCK", $iBlock);
$smarty->assign("LASTBLOCK", $aBlockData['blockNumber']);
$smarty->assign("DIFFICULTY", $dDifficulty);
$smarty->assign("TARGETDIFF", $config['difficulty']);
$smarty->assign("REWARD", $config['reward']);

if ($_SESSION['AUTHENTICATED']) {
  $smarty->assign("CONTENT", "authenticated.tpl");
} else {
  $smarty->assign("CONTENT", "default.tpl");
}
?>
