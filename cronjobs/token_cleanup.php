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

// Cleanup old expired tokens
if ($oToken->cleanupTokens()) {
  $oToken->deleted == 0 ? $log->logDebug('Did not find any expired tokens') : $log->logInfo('Deleted ' . $oToken->deleted . ' expired tokens');
} else {
  $log->logError('Failed to delete expired tokens: ' . $oToken->getCronError());
  // Treat as critical since tokens like password resets will never expire
  $monitoring->endCronjob($cron_name, 'E0074', 1, true, true);
}

// Cron cleanup and monitoring
require_once('cron_end.inc.php');
?>
