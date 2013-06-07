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

$aWorkers = $worker->getAllIdleWorkers();
if (empty($aWorkers)) {
  verbose("No idle workers found\n");
  exit;
}

foreach ($aWorkers as $aWorker) {
  $aData = $aWorker;
  $aData['username'] = $user->getUserName($aWorker['account_id']);
  $aData['email'] = $user->getUserEmail($aData['username']);
  if (!$notification->isNotified($aData)) {
    if (!$notification->addNotification('idle_worker', $aData) && $notification->sendMail('sebastian@grewe.ca', 'idle_worker', $aData))
      verbose("Unable to send notification: " . $notification->getError() . "\n");
  } else {
    verbose("Already notified for this worker\n");
  }
}
?>
