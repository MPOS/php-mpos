<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
die('Hacking attempt');

// {"pool_name":"Pool-X.eu","hashrate":"511128.99","workers":"2104","shares_this_round":92450,"last_block":"365294","network_hashrate":17327056.06}

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
