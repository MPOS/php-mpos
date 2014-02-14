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

// Cleanup old expired tokens
if ($notification->cleanupNotifications($setting->getValue('notifications_cleanup_time', 7))) {
  $notification->deleted == 0 ? $log->logDebug('Did not delete any old notifications') : $log->logInfo('Deleted ' . $notification->deleted . ' notifications');
} else {
  $log->logError('Failed to delete notifications: ' . $notification->getCronError());
  $monitoring->endCronjob($cron_name, 'E0074', 0, false, false);
}

// Cron cleanup and monitoring
require_once('cron_end.inc.php');
?>
