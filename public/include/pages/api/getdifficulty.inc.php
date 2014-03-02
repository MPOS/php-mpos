<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

// Fetch data from wallet
$bitcoin->can_connect() === true ? $dDifficulty = $bitcoin->getdifficulty() : $iDifficulty = 1;

// Output JSON format
echo $api->get_json($dDifficulty);

// Supress master template
$supress_master = 1;
?>
