<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$id = $user->checkApiKey($_REQUEST['api_key']);

// Output JSON format
$statistics->setGetCache(false);
$start = microtime(true);
$dPoolHashrate = $statistics->getCurrentHashrate(300);
$end = microtime(true);
$runtime = ($end - $start) * 1000;
$statistics->setGetCache(true);
echo json_encode(array('getpoolhashrate' => array(
  'runtime' => $runtime,
  'hashrate' => $dPoolHashrate,
)));

// Supress master template
$supress_master = 1;
?>
