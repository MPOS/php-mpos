<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Check how many blocks to fetch
$setting->getValue('statistics_block_count') ? $iLimit = $setting->getValue('statistics_block_count') : $iLimit = 20;

// Fetch latest blocks found, honor anon flag
$aBlocks = $statistics->getBlocksFound($iLimit);
foreach ($aBlocks as $iKey => $aBlockData) {
  if ($aBlockData['is_anonymous'] == 1) {
    $aBlocks[$iKey]['finder'] = 'anonymous';
    $aBlocks[$iKey]['worker_name'] = 'anonymous.anon';
  }
}

// Output JSON format
echo $api->get_json($aBlocks);

// Supress master template
$supress_master = 1;
?>
