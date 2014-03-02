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

/**
 * Simple script to fetch all user accounts and their coin addresses, then runs
 * them against the RPC to validate. Will allow admins to find users with invalid addresses.
 **/

// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');

// Fetch hashrates
$dNetworkHashrate = $bitcoin->getnetworkhashps() / 1000;
$dPoolHashrate = $statistics->getCurrentHashrate();
$iPercentage = round(100 / $dNetworkHashrate * $dPoolHashrate, 0);

if ($iPercentage >= 51) {
  echo 'Your pool has ' . $iPercentage . '% of the network hashrate. Registrations will be disabled.' . PHP_EOL;
  $setting->setValue('lock_registration', 1);
} else {
  $setting->setValue('lock_registration', 0);
}
?>
