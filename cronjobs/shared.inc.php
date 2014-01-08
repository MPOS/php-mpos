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

// MODIFY THIS
// We need to find our include files so set this properly
define("BASEPATH", "../public/");

/*****************************************************
 * No need to change beyond this point               *
 *****************************************************/

// Used in autoloading of API class, adding it to stop PHP warnings
$dStartTime = microtime(true);

// Our cron name
$cron_name = basename($_SERVER['PHP_SELF'], '.php');

// Our security check
define("SECURITY", 1);

// Include our configuration (holding defines for the requires)
require_once(BASEPATH . 'include/config/global.inc.php');

// We include all needed files here, even though our templates could load them themself
require_once(INCLUDE_DIR . '/autoloader.inc.php');

// Command line switches
array_shift($argv);
foreach ($argv as $option) {
  switch ($option) {
  case '-f':
    $monitoring->setStatus($cron_name . "_disabled", "yesno", 0);
    break;
  }
}

// Load 3rd party logging library for running crons
$log = new KLogger ( 'logs/' . $cron_name . '.txt' , KLogger::INFO );
$log->LogDebug('Starting ' . $cron_name);

// Load the start time for later runtime calculations for monitoring
$cron_start[$cron_name] = microtime(true);

// Check if our cron is activated
if ($monitoring->isDisabled($cron_name)) {
  $log->logFatal('Cronjob is currently disabled due to errors, use -f option to force running cron.');
  $monitoring->endCronjob($cron_name, 'E0018', 1, true, false);
}

// Mark cron as running for monitoring
$log->logDebug('Marking cronjob as running for monitoring');
$monitoring->setStatus($cron_name . '_starttime', 'date', time());

// Check if we need to halt our crons due to an outstanding upgrade
if ($setting->getValue('db_upgrade_required') == 1 || $setting->getValue('config_upgrade_required') == 1) {
  $log->logFatal('Cronjob is currently disabled due to required upgrades. Import any outstanding SQL files and check your configuration file.');
  $monitoring->endCronjob($cron_name, 'E0075', 0, true, false);
}

?>
