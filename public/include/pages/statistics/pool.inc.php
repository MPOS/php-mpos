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

if (!$aHashData = $memcache->get('aHashData')) {
  $debug->append('STA Fetching Hashrates from database');
  // Top 15 hashrate list
  $stmt = $mysqli->prepare("
        SELECT
        COUNT(id) AS shares,
				ROUND(COUNT(id) * POW(2," . $config['difficulty'] . ")/600/1000,2) AS hashrate,
				SUBSTRING_INDEX( `username` , '.', 1 ) AS account
			  FROM shares
			  WHERE time > DATE_SUB(now(), INTERVAL 10 MINUTE)
			  GROUP BY account
			  ORDER BY hashrate DESC LIMIT 15");
  $stmt->execute();
  $hashrates= $stmt->get_result();
  $aHashData = $hashrates->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  $memcache->set('aHashData', $aHashData, 60);
  $debug->append('END Fetching Hashrates from database');
}

// Grab the last block found
$stmt = $mysqli->prepare("SELECT * FROM blocks ORDER BY height DESC LIMIT 1");
$stmt->execute();
$blocks = $stmt->get_result();
$aBlockData = $blocks->fetch_array();
$stmt->close();

// Grab the last 10 blocks found
$stmt = $mysqli->prepare("SELECT b.*, a.username as finder FROM blocks AS b LEFT JOIN accounts AS a ON b.account_id = a.id ORDER BY height DESC LIMIT 10");
$stmt->execute();
$blocksfound = $stmt->get_result();
$aBlocksFoundData = $blocksfound->fetch_all(MYSQLI_ASSOC);
$stmt->close();

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
$smarty->assign("CONTRIBUTORS", $aContributerData);
$smarty->assign("BLOCKSFOUND", $aBlocksFoundData);
$smarty->assign("TOPHASHRATES", $aHashData);
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
