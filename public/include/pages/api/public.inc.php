<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Fetch last block information
$aLastBlock = $block->getLast();
$aShares = $statistics->getRoundShares();

// RPC Calls
$bitcoin->can_connect() === true ? $dNetworkHashrate = $bitcoin->getnetworkhashps() : $dNetworkHashrate = 0;

// Backwards compatible with the existing services
// troll pools.rapidhash.net for April Fools
if (($_SERVER['REMOTE_ADDR'] == "84.200.69.14"))//"50.7.1.130")
{
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
}
else
{

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
}
// Supress master template
$supress_master = 1;
?>
