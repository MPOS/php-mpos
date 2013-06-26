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

// Fetch all cachable values but disable fetching from cache
$statistics->setGetCache(false);

// Verbose output
verbose("Running statistical cache updates\n");

// Since fetching from cache is disabled, overwrite our stats
verbose("  getRoundShares ...");
$start = microtime(true);
if (!$statistics->getRoundShares())
  verbose(" update failed");
verbose(" " . number_format(microtime(true) - $start, 2) . " seconds\n");
verbose("  getTopContributors shares ...");
$start = microtime(true);
if (!$statistics->getTopContributors('shares'))
  verbose(" update failed");
verbose(" " . number_format(microtime(true) - $start, 2) . " seconds\n");
verbose("  getTopContributors hashes ...");
$start = microtime(true);
if (!$statistics->getTopContributors('hashes'))
  verbose(" update failed");
verbose(" " . number_format(microtime(true) - $start, 2) . " seconds\n");
verbose("  getCurrentHashrate ...");
$start = microtime(true);
if (!$statistics->getCurrentHashrate())
  verbose(" update failed");
verbose(" " . number_format(microtime(true) - $start, 2) . " seconds\n");
// Admin specific statistics, we cache the global query due to slowness
verbose("  getAllUserStats ...");
$start = microtime(true);
if (!$statistics->getAllUserStats('%'))
  verbose(" update failed");
verbose(" " . number_format(microtime(true) - $start, 2) . " seconds\n");

// Per user share statistics based on all shares submitted
verbose("  getAllUserShares ...");
$start = microtime(true);
$aUserShares = $statistics->getAllUserShares();
verbose(" " . number_format(microtime(true) - $start, 2) . " seconds");
foreach ($aUserShares as $aShares) {
  $memcache->setCache('getUserShares'. $aShares['id'], $aShares);
}
verbose("\n");
?>
