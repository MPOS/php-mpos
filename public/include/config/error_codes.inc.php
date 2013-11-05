<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

$aErrorCodes['OK'] = 'OK';
$aErrorCodes['E0001'] = 'Out of Order Share Detected';
$aErrorCodes['E0002'] = 'Upstream shares not found';
$aErrorCodes['E0003'] = 'Failed to change shares order';
$aErrorCodes['E0004'] = 'Failed to reset previous block';
$aErrorCodes['E0005'] = 'Unable to fetch blocks upstream share';
$aErrorCodes['E0006'] = 'Unable to conenct to RPC server backend';
$aErrorCodes['E0007'] = 'Out of Order Share detected, autofixed';
$aErrorCodes['E0008'] = 'Failed to delete archived shares';
$aErrorCodes['E0009'] = 'Cron disabled by admin';
$aErrorCodes['E0010'] = 'Unable set payout as processed';
$aErrorCodes['E0011'] = 'No new unaccounted blocks';
$aErrorCodes['E0012'] = 'No share_id found for block';
$aErrorCodes['E0013'] = 'No shares found for block';
$aErrorCodes['E0014'] = 'Failed marking block as accounted';
$aErrorCodes['E0015'] = 'Potential Double Payout detected';
$aErrorCodes['E0016'] = 'Failed to delete accounted shares';
$aErrorCodes['E0017'] = 'Failed to update Uptime Robot status';
$aErrorCodes['E0018'] = 'Cron disbaled due to errors';
$aErrorCodes['E0019'] = "SQL Query failed: %s";
$aErrorCodes[''] = '';
$aErrorCodes[''] = '';
?>
