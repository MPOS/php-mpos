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
if (!$statistics->getRoundShares())
  verbose(" update failed");
verbose("\n  getTopContributors shares ...");
if (!$statistics->getTopContributors('shares'))
  verbose(" update failed");
verbose("\n  getTopContributors hashes ...");
if (!$statistics->getTopContributors('hashes'))
  verbose(" update failed");
verbose("\n  getCurrentHashrate ...");
if (!$statistics->getCurrentHashrate())
  verbose(" update failed");
// Admin specific statistics, we cache the global query due to slowness
verbose("\n  getAllUserStats ...");
if (!$statistics->getAllUserStats('%'))
  verbose(" update failed");
verbose("\n");

// Per user share statistics based on all shares submitted
verbose("  getUserShares ...\n");
$stmt = $mysqli->prepare("SELECT DISTINCT SUBSTRING_INDEX( `username` , '.', 1 ) AS username FROM " . $share->getTableName());
if ($stmt && $stmt->execute() && $result = $stmt->get_result()) {
  while ($row = $result->fetch_assoc()) {
    verbose("    " . $row['username'] . " ...");
    if (!$statistics->getUserShares($user->getUserId($row['username'])))
      verbose(" update failed");
    verbose("\n");
  }
}
?>
