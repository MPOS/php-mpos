<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Fetch last block information
$aLastBlock = $block->getLast();
$aShares = $statistics->getRoundShares();

// RPC Calls
$bitcoin->can_connect() === true ? $dNetworkHashrate = $bitcoin->getnetworkhashps() : $dNetworkHashrate = 0;

// Backwards compatible with the existing services
echo json_encode(
  array(
    'pool_name' => $setting->getValue('website_name'),
    'hashrate' => $statistics->getCurrentHashrate(),
    'workers' => $worker->getCountAllActiveWorkers(),
    'shares_this_round' => $aShares['valid'],
    'last_block' => $aLastBlock['height'],
    'network_hashrate' => $dNetworkHashrate
  )
);

// Supress master template
$supress_master = 1;
?>
