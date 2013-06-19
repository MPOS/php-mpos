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

// Since fetching from cache is disabled, overwrite our stats
if (!$statistics->getRoundShares())
  verbose("Unable to fetch and store current round shares\n");
if (!$statistics->getTopContributors('shares'))
  verbose("Unable to fetch and store top share contributors\n");
if (!$statistics->getTopContributors('hashes'))
  verbose("Unable to fetch and store top hashrate contributors\n");
if (!$statistics->getCurrentHashrate())
  verbose("Unable to fetch and store pool hashrate\n");
// Admin specific statistics, we cache the global query due to slowness
if (!$statistics->getAllUserStats('%'))
  verbose("Unable to fetch and store admin panel full user list\n");

// Per user share statistics based on all shares submitted
$stmt = $mysqli->prepare("SELECT DISTINCT SUBSTRING_INDEX( `username` , '.', 1 ) AS username FROM " . $share->getTableName());
if ($stmt && $stmt->execute() && $result = $stmt->get_result()) {
  while ($row = $result->fetch_assoc()) {
    if (!$statistics->getUserShares($user->getUserId($row['username'])))
      verbose("Failed to fetch and store user stats for " . $row['username'] . "\n");
  }
}
?>
