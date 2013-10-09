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

if ($price = $tools->getPrice()) {
  $log->logInfo("Price update: found $price as price");
  if (!$setting->setValue('price', $price))
    $log->logError("unable to update value in settings table");
} else {
  $log->logFatal("failed to fetch API data: " . $tools->getError());
}

require_once('cron_end.inc.php');
?>
