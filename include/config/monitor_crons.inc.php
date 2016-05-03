<?php

// Small helper array that may be used on some page controllers to
// fetch the crons we wish to monitor
switch ($config['payout_system']) {
  case 'prop':
    $sPayoutSystem = 'proportional_payout';
    break;
  default: // pps && pplns land here
    $sPayoutSystem = $config['payout_system'] . '_payout';
}

$aMonitorCrons = array('statistics','tickerupdate','notifications','tables_cleanup','findblock',$sPayoutSystem,'blockupdate','payouts');
