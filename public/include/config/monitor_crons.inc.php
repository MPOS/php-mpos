<?php

// Small helper array that may be used on some page controllers to
// fetch the crons we wish to monitor
$aMonitorCrons = array('statistics','payouts','tables_cleanup','blockupdate','findblock','notifications','tickerupdate');

switch ($config['payout_system']) {
case 'pplns':
    $aMonitorCrons[] = $config['payout_system'] . '_payout';
      break;
case 'pps':
    $aMonitorCrons[] = $config['payout_system'] . '_payout';
      break;
case 'prop':
    $aMonitorCrons[] = 'proportional_payout';
      break;
}
