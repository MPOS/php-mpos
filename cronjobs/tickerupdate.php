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
require_once(BASEPATH . CLASS_DIR . '/tools.class.php');

verbose("Running ticket updates\n");
if ($aData = $tools->getApi($config['price']['url'], $config['price']['target'])) {
  if (!$setting->setValue('price', $aData['ticker']['last']))
    verbose("ERR Table update failed");
} else {
  verbose("ERR Failed download JSON data from " . $config['price']['url'].$config['price']['target'] . "\n");
}

?>
