<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);
$username = $user->getUsername($user_id);

// Output JSON format
$data = array(
  'mine' => $statistics->getHourlyHashrateByAccount($username, $user_id),
  'pool' => $statistics->getHourlyHashrateByPool()
);

echo $api->get_json($data);

// Supress master template
$supress_master = 1;
?>
