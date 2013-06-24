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

// Include all settings and classes
require_once('shared.inc.php');

verbose("Running system notifications\n");

verbose("  IDLE Worker Notifications ...");
// Find all IDLE workers
$aWorkers = $worker->getAllIdleWorkers();
if (empty($aWorkers)) {
  verbose(" no idle workers found\n");
} else {
  verbose(" found " . count($aWorkers) . " IDLE workers\n");
  foreach ($aWorkers as $aWorker) {
    $aData = $aWorker;
    $aData['username'] = $user->getUserName($aWorker['account_id']);
    $aData['subject'] = 'IDLE Worker : ' . $aWorker['username'];
    $aData['worker'] = $aWorker['username'];
    $aData['email'] = $user->getUserEmail($aData['username']);
    verbose("    " . $aWorker['username'] . "...");
    if (!$notification->sendNotification($aWorker['account_id'], 'idle_worker', $aData)) {
      verbose(" " . $notification->getError() . "\n");
    } else {
      verbose(" sent\n");
    }
  }
}


verbose("  Reset IDLE Worker Notifications ...");
// We notified, lets check which recovered
$aNotifications = $notification->getAllActive('idle_worker');
if (!empty($aNotifications)) {
  verbose(" found " . count($aNotifications) . " active notification(s)\n");
  foreach ($aNotifications as $aNotification) {
    $aData = json_decode($aNotification['data'], true);
    $aWorker = $worker->getWorker($aData['id']);
    verbose("    " . $aWorker['username'] . " ...");
    if ($aWorker['active'] == 1) {
      if ($notification->setInactive($aNotification['id'])) {
        verbose(" updated #" . $aNotification['id'] . " for " . $aWorker['username'] . " as inactive\n");
      } else {
        verbose(" failed to update #" . $aNotification['id'] . " for " . $aWorker['username'] . "\n");
      }
    } else {
      verbose(" still inactive\n");
    }
  }
} else {
  verbose(" no active IDLE worker notifications\n");
}
?>
