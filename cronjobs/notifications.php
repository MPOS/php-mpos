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
  $monitoring->endCronjob($cron_name, 'E0009', 0, true, false);
}

if ($setting->getValue('notifications_disable_idle_worker') != 1) {
  $log->logDebug("  IDLE Worker Notifications ...");
  // Find all IDLE workers
  $aWorkers = $worker->getAllIdleWorkers();
  if (empty($aWorkers)) {
    $log->logDebug(" no idle workers found");
  } else {
    $log->logInfo(" found " . count($aWorkers) . " IDLE workers");
    foreach ($aWorkers as $aWorker) {
      $aData = $aWorker;
      $aData['username'] = $user->getUserName($aWorker['account_id']);
      $aData['subject'] = 'IDLE Worker : ' . $aWorker['username'];
      $aData['worker'] = $aWorker['username'];
      $aData['email'] = $user->getUserEmail($aData['username']);
      $log->logDebug("    " . $aWorker['username'] . "...");
      if (!$notification->sendNotification($aWorker['account_id'], 'idle_worker', $aData))
        $log->logDebug("    Failed sending notifications: " . $notification->getCronError());
    }
  }


  $log->logDebug("  Reset IDLE Worker Notifications ...");
  // We notified, lets check which recovered
  $aNotifications = $notification->getAllActive('idle_worker');
  if (!empty($aNotifications)) {
    $log->logInfo(" found " . count($aNotifications) . " active notification(s)");
    foreach ($aNotifications as $aNotification) {
      $aData = json_decode($aNotification['data'], true);
      $aWorker = $worker->getWorker($aData['id']);
      $log->logDebug("    " . $aWorker['username'] . " ...");
      if ($aWorker['hashrate'] > 0) {
        if ($notification->setInactive($aNotification['id'])) {
          $log->logDebug(" updated #" . $aNotification['id'] . " for " . $aWorker['username'] . " as inactive");
        } else {
          $log->logError(" failed to update #" . $aNotification['id'] . " for " . $aWorker['username']);
        }
      } else {
        $log->logDebug(" still inactive");
      }
    }
  } else {
    $log->logDebug(" no active IDLE worker notifications");
  }
}

require_once('cron_end.inc.php');
?>
