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

if ($setting->getValue('disable_notifications') == 1) {
  $monitoring->setStatus($cron_name . "_active", "yesno", 0);
  $monitoring->setStatus($cron_name . "_message", "message", "Cron disabled by admin");
  $monitoring->setStatus($cron_name . "_status", "okerror", 0);
  exit(0);
}

$log->logDebug("  IDLE Worker Notifications ...");
// Find all IDLE workers
$aWorkers = $worker->getAllIdleWorkers();
if (empty($aWorkers)) {
  $log->logDebug(" no idle workers found\n");
} else {
  $log->logInfo(" found " . count($aWorkers) . " IDLE workers\n");
  foreach ($aWorkers as $aWorker) {
    $aData = $aWorker;
    $aData['username'] = $user->getUserName($aWorker['account_id']);
    $aData['subject'] = 'IDLE Worker : ' . $aWorker['username'];
    $aData['worker'] = $aWorker['username'];
    $aData['email'] = $user->getUserEmail($aData['username']);
    $log->logInfo("    " . $aWorker['username'] . "...");
    if (!$notification->sendNotification($aWorker['account_id'], 'idle_worker', $aData))
      $log->logError("    Failed sending notifications: " . $notification->getError() . "\n");
  }
}


$log->logDebug("  Reset IDLE Worker Notifications ...");
// We notified, lets check which recovered
$aNotifications = $notification->getAllActive('idle_worker');
if (!empty($aNotifications)) {
  $log->logInfo(" found " . count($aNotifications) . " active notification(s)\n");
  foreach ($aNotifications as $aNotification) {
    $aData = json_decode($aNotification['data'], true);
    $aWorker = $worker->getWorker($aData['id']);
    $log->logInfo("    " . $aWorker['username'] . " ...");
    if ($aWorker['hashrate'] > 0) {
      if ($notification->setInactive($aNotification['id'])) {
        $log->logInfo(" updated #" . $aNotification['id'] . " for " . $aWorker['username'] . " as inactive\n");
      } else {
        $log->logInfo(" failed to update #" . $aNotification['id'] . " for " . $aWorker['username'] . "\n");
      }
    } else {
      $log->logInfo(" still inactive\n");
    }
  }
} else {
  $log->logDebug(" no active IDLE worker notifications\n");
}

require_once('cron_end.inc.php');
?>
