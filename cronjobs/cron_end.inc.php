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

// Monitoring cleanup and status update
$monitoring->setStatus($cron_name . "_message", "message", "OK");
$monitoring->setStatus($cron_name . "_status", "okerror", 0);
$monitoring->setStatus($cron_name . "_runtime", "time", microtime(true) - $cron_start[$cron_name]);
$monitoring->setStatus($cron_name . "_endtime", "date", time());
// Mark cron as running for monitoring
$monitoring->setStatus($cron_name . '_active', "yesno", 0);
?>
