<?php

// Small helper array that may be used on some page controllers to
// fetch the crons we wish to monitor
switch ($config['payout_system']) {
  case 'pplns':
    $sPayoutSystem = $config['payout_system'] . '_payout';
    break;
  case 'pps':
    $sPayoutSystem = $config['payout_system'] . '_payout';
    break;
  case 'prop':
    $sPayoutSystem = 'proportional_payout';
    break;
}

$aMonitorCrons = array('statistics','tickerupdate','notifications','tables_cleanup','findblock',$sPayoutSystem,'blockupdate','payouts');
