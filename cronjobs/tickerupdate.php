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

// Include additional file not set in autoloader
require_once(CLASS_DIR . '/tools.class.php');

// Header and info
$log->logInfo('Running periodic tasks to update database values for GUI access');
$strLogMask = "| %-25.25s | %15.15s | %8.8s | %-6.6s | %-80.80s |";
$log->logInfo(sprintf($strLogMask, 'Method', 'Value', 'Runtime', 'Status', 'Message'));

empty($config['price']['enabled']) ? $tickerupdate = false : $tickerupdate = $config['price']['enabled'];

// Fetch latest coin price via API call
$start = microtime(true);
if ($tickerupdate) {
  $message = 'Updated latest ' . $config['currency'] . ' price from ' . $config['price']['url'] . ' API';
  $status = 'OK';
  if ($price = $tools->getPrice()) {
    if (!$setting->setValue('price', $price)) {
      $message = 'Unable to store new price value: ' . $setting->getCronError();
      $status = 'ERROR';
    }
  } else {
    $message = 'Failed to fetch price from API: ' . $tools->getCronError();
    $status = 'ERROR';
  }
} else {
  $message = 'Tickerupdate is disabled';
  $status = 'OK';
  $price = 0;
}
$log->logInfo(sprintf($strLogMask, 'Price Update', $price, number_format(microtime(true) - $start, 3), $status, $message));


// Update Uptime Robot status in Settings table via API call
$start = microtime(true);
$message = 'Updated Uptime Robot status from API';
$status = 'OK';
if ($api_keys = $setting->getValue('monitoring_uptimerobot_api_keys')) {
  if (!strstr($api_keys, 'MONITOR_API_KEY|MONITOR_NAME')) {
    $monitoring->setTools($tools);
    if (!$monitoring->storeUptimeRobotStatus()) {
      $message = $monitoring->getCronError();
      $status = 'ERROR';
    }
  }
} else {
  $status = 'SKIPED';
  $message = 'Missing API keys and monitor names';
}
$log->logInfo(sprintf($strLogMask, 'Uptime Robot', 'n/a', number_format(microtime(true) - $start, 3), $status, $message));

require_once('cron_end.inc.php');
?>
