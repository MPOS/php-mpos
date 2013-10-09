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

// Per user share statistics based on all shares submitted
$start = microtime(true);
if ( ! $aAllUserShares = $statistics->getAllUserShares() )
  $log->logError('getAllUserShares update failed');
$log->logInfo("getAllUserShares " . number_format(microtime(true) - $start, 2) . " seconds");

$start = microtime(true);
if (!$statistics->getTopContributors('hashes'))
  $log->logError("getTopContributors hashes update failed");
$log->logInfo("getTopContributors hashes " . number_format(microtime(true) - $start, 2) . " seconds");

$start = microtime(true);
if (!$statistics->getCurrentHashrate())
  $log->logError("getCurrentHashrate update failed");
$log->logInfo("getCurrentHashrate " . number_format(microtime(true) - $start, 2) . " seconds");

/*
// Admin specific statistics, we cache the global query due to slowness
$start = microtime(true);
if (!$statistics->getAllUserStats('%'))
  $log->logError("getAllUserStats update failed");
$log->logInfo("getAllUserStats " . number_format(microtime(true) - $start, 2) . " seconds");
*/

require_once('cron_end.inc.php');
?>
