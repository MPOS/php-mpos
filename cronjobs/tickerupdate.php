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

// Include additional file not set in autoloader
require_once(CLASS_DIR . '/tools.class.php');

verbose("Running updates\n");
verbose("  Price API Call ... ");
if ($price = $tools->getPrice()) {
  verbose("found $price as price\n");
  if (!$setting->setValue('price', $price))
    verbose("unable to update value in settings table\n");
} else {
  verbose("failed to fetch API data: " . $tools->getError() . "\n");
}

?>
