<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Fetch last block information
$aLastBlock = $block->getLast();
$aShares = $statistics->getRoundShares();

echo json_encode(
  array(
    'pool_name' => $config['website']['name'],
    'hashrate' => $statistics->getCurrentHashrate(),
    'workers' => $worker->getCountAllActiveWorkers(),
    'shares_this_round' => $aShares['valid'],
    'last_block' => $aLastBlock['height'],
    'network_hashrate' => '0'
  )
);

// Supress master template
$supress_master = 1;
?>
