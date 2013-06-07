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

// Find all IDLE workers
$aWorkers = $worker->getAllIdleWorkers();
if (empty($aWorkers)) {
  verbose("No idle workers found\n");
} else {
  foreach ($aWorkers as $aWorker) {
    $aData = $aWorker;
    $aData['username'] = $user->getUserName($aWorker['account_id']);
    $aData['subject'] = 'IDLE Worker : ' . $aWorker['username'];
    $aData['email'] = $user->getUserEmail($aData['username']);
    if ( $notification->isNotified($aData) ) {
      verbose("Worker already notified\n");
      continue;
    }
    if ($notification->addNotification('idle_worker', $aData) && $notification->sendMail($aData['email'], 'idle_worker', $aData)) {
        verbose ("Notified " . $aData['email'] . " for IDLE worker " . $aWorker['username'] . "\n");
      } else {
        verbose("Unable to send notification: " . $notification->getError() . "\n");
      }
  }
}

// We notified, lets check which recovered
$aNotifications = $notification->getAllActive();
foreach ($aNotifications as $aNotification) {
  $aData = json_decode($aNotification['data'], true);
  $aWorker = $worker->getWorker($aData['id']);
  if ($aWorker['active'] == 1) {
    if ($notification->setInactive($aNotification['id'])) {
      verbose("Marked notification " . $aNotification['id'] . " as inactive\n");
    } else {
      verbose("Failed to set notification inactive for " . $aWorker['username'] . "\n");
    }
  }
}

?>
