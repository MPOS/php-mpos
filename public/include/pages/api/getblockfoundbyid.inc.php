<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']));

$blockId =  @$_GET['id'];

// Fetch latest blocks found, honor anon flag
$aBlocks = $statistics->getBlockFoundById($blockId);
foreach ($aBlocks as $iKey => $aBlockData) {
  if ($aBlockData['is_anonymous'] == 1) {
    $aBlocks[$iKey]['finder'] = 'awesomeshibe';
    $aBlocks[$iKey]['worker_name'] = 'awesomeshibe.worker';
  }
}

// Output JSON format
echo $api->get_json($aBlocks);
// Supress master template
$supress_master = 1;
?>
