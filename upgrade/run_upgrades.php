#!/usr/bin/php
<?php
/* Upgarde script for https://github.com/MPOS/php-mpos/issues/1981 */

// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');

// Fetch current version
$db_version_now = $setting->getValue('DB_VERSION');

// Helper function
function run_db_upgrade($db_version_now) {
  // Find upgrades
  $files = glob(dirname(__FILE__) . "/definitions/${db_version_now}_to_*");
  if (count($files) == 0) die('No upgrade definitions found for ' . $db_version_now . PHP_EOL);
  foreach ($files as $file) {
    $db_version_to = preg_replace("/(.*)\/definitions\/${db_version_now}_to_/", '', $file);
    $db_version_to = preg_replace("/.inc.php/", '', $db_version_to);
    $run_string = preg_replace("/\./", '', $db_version_to);
    if (!require_once($file)) die('Failed to load upgrade definition: ' . $file);
    echo "+ Running upgrade from $db_version_now to $db_version_to" . PHP_EOL;
    $run = "run_$run_string";
    $run();
    run_db_upgrade($db_version_to);
  }
}

// Initial caller
run_db_upgrade($db_version_now);
?>
