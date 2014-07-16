<?php
// Small helper array that may be used on some page controllers to
// fetch the crons we wish to monitor
$sPayoutSystem = $config['payout_system'] . '_payout';
$aMonitorCrons = array('statistics','tickerupdate','notifications','tables_cleanup','findblock',$sPayoutSystem,'blockupdate','payouts');
?>
