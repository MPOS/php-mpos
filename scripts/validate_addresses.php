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

// Fetch all users
$users = $user->getAllAssoc();

// Duplicate address check
$aAllAddresses = array();

// Table mask
$mask = "| %-35.35s | %-35.35s | %-40.40s | %-7.7s |\n";
echo 'Validating all coin addresses. This may take some time.' . PHP_EOL . PHP_EOL;

printf($mask, 'Username', 'E-Mail', 'Address', 'Status');
foreach ($users as $aData) {
  if (empty($aData['coin_address']) && $aData['is_locked'] == 0) {
    $status = 'UNSET';
  } else if ($aData['is_locked'] == 1) {
    $status = 'LOCKED';
  } else {
    if ($bitcoin->validateaddress($aData['coin_address'])) {
      $status = 'VALID';
    } else {
      $status = 'INVALID';
    }
  }
  // Duplicate check
  if (in_array($aData['coin_address'], $aAllAddresses)) {
    $status = 'DUPE';
  } else if (!empty($aData['coin_address'])) {
    $aAllAddresses[] = $aData['coin_address'];
  }
  printf($mask, $aData['username'], $aData['email'], $aData['coin_address'], $status);
}
