<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Defaults to get rid of PHP Notice warnings
$dDifficulty = 1;
$aRoundShares = 1;

// Only run these if the user is logged in
$aRoundShares = $statistics->getRoundShares();
if ($bitcoin->can_connect() === true) {
  $dDifficulty = $bitcoin->query('getdifficulty');
  if (is_array($dDifficulty) && array_key_exists('proof-of-work', $dDifficulty))
    $dDifficulty = $dDifficulty['proof-of-work'];
}

// Always fetch this since we need for ministats header
$bitcoin->can_connect() === true ? $dNetworkHashrate = $bitcoin->query('getnetworkhashps') : $dNetworkHashrate = 0;

// Fetch some data
if (!$iCurrentActiveWorkers = $worker->getCountAllActiveWorkers()) $iCurrentActiveWorkers = 0;
$iCurrentPoolHashrate =  $statistics->getCurrentHashrate();
$iCurrentPoolShareRate = $statistics->getCurrentShareRate();

// Avoid confusion, ensure our nethash isn't higher than poolhash
if ($iCurrentPoolHashrate > $dNetworkHashrate) $dNetworkHashrate = $iCurrentPoolHashrate;

// Make it available in Smarty
$smarty->assign('CONTENT', 'default.tpl');
?>
