#!/usr/bin/php
<?php

/*

Copyright:: 2013, Sebastian Grewe

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

 */

// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');

// Header
$strLogMask = "| %-20.20s | %10.10s | %8.8s | %6.6s | %-40s |";
$log->logInfo(sprintf($strLogMask, 'Process', 'Affected', 'Runtime', 'Status', 'Message'));

// Cleanup old notifications
$start = microtime(true);
$status = 'OK';
$message = '';
$affected = 0;
if ($notification->cleanupNotifications($setting->getValue('notifications_cleanup_time', 7))) {
  $affected = $notification->deleted;
  $affected == 0 ? $message = 'No notifications deleted' : $message = 'Deleted notifications older than ' . $setting->getValue('notifications_cleanup_time', 7) . ' days';
} else {
  $message = 'Failed to delete notifications: ' . $notification->getCronError();
  $status = 'ERROR';
  $monitoring->endCronjob($cron_name, 'E0074', 0, false, false);
}
$log->logInfo(sprintf($strLogMask, 'cleanupNotifications', $affected, number_format(microtime(true) - $start, 3), $status, $message));

// Cleanup old expired tokens
$start = microtime(true);
$status = 'OK';
$message = '';
$affected = 0;
if ($oToken->cleanupTokens()) {
  $affected = $oToken->deleted;
  $affected == 0 ? $message = 'No tokens deleted' : $message = 'Deleted expired tokens';
} else {
  $message = 'Failed to delete notifications: ' . $oToken->getCronError();
  $status = 'ERROR';
  $monitoring->endCronjob($cron_name, 'E0074', 0, false, false);
}
$log->logInfo(sprintf($strLogMask, 'cleanupTokens', $affected, number_format(microtime(true) - $start, 3), $status, $message));

// Clenaup shares archive
$start = microtime(true);
$status = 'OK';
$message = '';
$affected = $share->purgeArchive();
if ($affected === false) {
  $message = 'Failed to delete notifications: ' . $oToken->getCronError();
  $status = 'ERROR';
  $monitoring->endCronjob($cron_name, 'E0008', 0, false, false);
} else {
  $affected == 0 ? $message = 'No shares deleted' : $message = 'Deleted old shares';
}
$log->logInfo(sprintf($strLogMask, 'purgeArchive', $affected, number_format(microtime(true) - $start, 3), $status, $message));


// Cron cleanup and monitoring
require_once('cron_end.inc.php');
?>
