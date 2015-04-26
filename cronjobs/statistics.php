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
$log->logInfo('Running statistical queries, errors may just mean no shares were available');
$strLogMask = "| %-33.33s | %8.8s | %-6.6s |";
$log->logInfo(sprintf($strLogMask, 'Method', 'Runtime', 'Status'));

// Per user share statistics based on all shares submitted
$start = microtime(true);
$statistics->getAllUserShares() ? $status = 'OK' : $status = 'ERROR';
$log->logInfo(sprintf($strLogMask, 'getAllUserShares', number_format(microtime(true) - $start, 3), $status));

// Get all user hashrate statistics for caching
$start = microtime(true);
$statistics->fetchAllUserMiningStats() ? $status = 'OK' : $status = 'ERROR';
$log->logInfo(sprintf($strLogMask, 'fetchAllUserMiningStats', number_format(microtime(true) - $start, 3), $status));

// Store our statistical data into our `statistics_users` table
$start = microtime(true);
$statistics->storeAllUserMiningStatsSnapshot($statistics->getAllUserMiningStats()) ? $status = 'OK' : $status = 'ERROR';
$log->logInfo(sprintf($strLogMask, 'storeAllUserMiningStatsSnapshot', number_format(microtime(true) - $start, 3), $status));

// Get stats for pool overview
$start = microtime(true);
$statistics->getTopContributors('hashes') ? $status = 'OK' : $status = 'ERROR';
$log->logInfo(sprintf($strLogMask, 'getTopContributors(hashes)', number_format(microtime(true) - $start, 3), $status));

$start = microtime(true);
$statistics->getCurrentHashrate() ? $status = 'OK' : $status = 'ERROR';
$log->logInfo(sprintf($strLogMask, 'getTopContributors(shares)', number_format(microtime(true) - $start, 3), $status));

require_once('cron_end.inc.php');
