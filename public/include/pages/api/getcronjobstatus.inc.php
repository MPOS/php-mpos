<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Check user token
$user_id = $api->checkAccess($user->checkApiKey($_REQUEST['api_key']), @$_REQUEST['id']);

if (!$user->isAdmin($user_id)) {
  die("404 Page not found");
}

// Default crons to monitor
$aCrons = array('statistics','payouts','token_cleanup','archive_cleanup','blockupdate','findblock','notifications','tickerupdate','liquid_payout');

// Special cases, only add them if activated
switch ($config['payout_system']) {
case 'pplns':
  $aCrons[] = $config['payout_system'] . '_payout';
  break;
case 'pps':
  $aCrons[] = $config['payout_system'] . '_payout';
  break;
case 'prop':
  $aCrons[] = 'proportional_payout';
  break;
}

// Data array for template
foreach ($aCrons as $strCron) {
  $aCronStatus[$strCron] = array(
    'disabled' => $monitoring->getStatus($strCron . '_disabled'),
    'exit' => $monitoring->getStatus($strCron . '_status'),
    'active' => $monitoring->getStatus($strCron . '_active'),
    'runtime' => $monitoring->getStatus($strCron . '_runtime'),
    'starttime' => $monitoring->getStatus($strCron . '_starttime'),
    'endtime' => $monitoring->getStatus($strCron . '_endtime'),
    'message' => $monitoring->getStatus($strCron . '_message'),
  );
}
echo $api->get_json($aCronStatus);

// Supress master template
$supress_master = 1;

?>
