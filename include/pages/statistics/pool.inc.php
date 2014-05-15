<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Fetch data from wallet, always run this check
if ($bitcoin->can_connect() === true){
  $dDifficulty = $bitcoin->getdifficulty();
  $dNetworkHashrate = $bitcoin->getnetworkhashps();
  $iBlock = $bitcoin->getblockcount();
  is_int($iBlock) && $iBlock > 0 ? $sBlockHash = $bitcoin->getblockhash($iBlock) : $sBlockHash = '';
} else {
  $dDifficulty = 1;
  $dNetworkHashrate = 1;
  $iBlock = 0;
  $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to wallet RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 'alert alert-danger');
}

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);

  // Top share contributors
  $aContributorsShares = $statistics->getTopContributors('shares', 15);

  // Top hash contributors
  $aContributorsHashes = $statistics->getTopContributors('hashes', 15);

  // Grab the last 5 blocks found as a quick overview
  $iLimit = 10;
  $aBlocksFoundData = $statistics->getBlocksFound($iLimit);
  count($aBlocksFoundData) > 0 ? $aBlockData = $aBlocksFoundData[0] : $aBlockData = array();

  // Estimated time to find the next block
  $iCurrentPoolHashrate =  $statistics->getCurrentHashrate();

  // Time in seconds, not hours, using modifier in smarty to translate
  $iCurrentPoolHashrate > 0 ? $iEstTime = $statistics->getExpectedTimePerBlock('pool', $iCurrentPoolHashrate) : $iEstTime = 0;

  // Time since last block
  if (!empty($aBlockData)) {
    $dTimeSinceLast = (time() - $aBlockData['time']);
    if ($dTimeSinceLast < 0) $dTimeSinceLast = 0;
  } else {
    $dTimeSinceLast = 0;
  }

  // Block average reward or fixed
  $reward = $config['reward_type'] == 'fixed' ? $config['reward'] : $block->getAverageAmount();

    // Round progress
  $iEstShares = $statistics->getEstimatedShares($dDifficulty);
  $aRoundShares = $statistics->getRoundShares();
  if ($iEstShares > 0 && $aRoundShares['valid'] > 0) {
    $dEstPercent = round(100 / $iEstShares * $aRoundShares['valid'], 2);
  } else {
    $dEstPercent = 0;
  }

  $dExpectedTimePerBlock = $statistics->getExpectedTimePerBlock();
  $dEstNextDifficulty = $statistics->getExpectedNextDifficulty();
  $iBlocksUntilDiffChange = $statistics->getBlocksUntilDiffChange();

  // Propagate content our template
  $smarty->assign("ESTTIME", $iEstTime);
  $smarty->assign("TIMESINCELAST", $dTimeSinceLast);
  $smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
  $smarty->assign("BLOCKLIMIT", $iLimit);
  $smarty->assign("CONTRIBSHARES", $aContributorsShares);
  $smarty->assign("CONTRIBHASHES", $aContributorsHashes);
  $smarty->assign("CURRENTBLOCK", $iBlock);
  $smarty->assign("CURRENTBLOCKHASH", @$sBlockHash);
  $smarty->assign('NETWORK', array('difficulty' => $dDifficulty, 'block' => $iBlock, 'EstNextDifficulty' => $dEstNextDifficulty, 'EstTimePerBlock' => $dExpectedTimePerBlock, 'BlocksUntilDiffChange' => $iBlocksUntilDiffChange));
  $smarty->assign('ESTIMATES', array('shares' => $iEstShares, 'percent' => $dEstPercent));
  if (count($aBlockData) > 0) {
    $smarty->assign("LASTBLOCK", $aBlockData['height']);
    $smarty->assign("LASTBLOCKHASH", $aBlockData['blockhash']);
  } else {
    $smarty->assign("LASTBLOCK", 0);
  }
  $smarty->assign("DIFFICULTY", $dDifficulty);
  $smarty->assign("REWARD", $reward);
} else {
  $debug->append('Using cached page', 3);
}

switch($setting->getValue('acl_pool_statistics', 1)) {
case '0':
  if ($user->isAuthenticated()) {
    $smarty->assign("CONTENT", "default.tpl");
  }
  break;
case '1':
  $smarty->assign("CONTENT", "default.tpl");
  break;
case '2':
  $_SESSION['POPUP'][] = array('CONTENT' => 'Page currently disabled. Please try again later.', 'TYPE' => 'alert alert-danger');
  $smarty->assign("CONTENT", "");
  break;
}
