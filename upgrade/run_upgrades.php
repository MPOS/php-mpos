#!/usr/bin/php
<?php
/* Upgarde script for https://github.com/MPOS/php-mpos/issues/1981 */

// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');

// Fetch current version
$db_version_now = $setting->getValue('DB_VERSION');

// Helper functions
function run_db_upgrade($db_version_now) {
  // Dirty, but need it here
  global $setting;

  // Drop caches from our settings for live data
  $setting->flushCache();

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
    if (function_exists($run)) {
      $run();
      run_db_upgrade($db_version_to);
    } else {
      echo 'Could find upgrade function ' . $run . '!' . PHP_EOL;
      exit(1);
    }
  }
}
function execute_db_upgrade($aSql) {
  global $mysqli;
  // Run the upgrade
  echo '- Starting database migration ' . PHP_EOL;
  foreach ($aSql as $sql) {
    echo '-  Preparing: ' . $sql . PHP_EOL;
    $stmt = $mysqli->prepare($sql);
    if ($stmt && $stmt->execute()) {
      echo '-    success' . PHP_EOL;
    } else {
      echo '-    failed: ' . $mysqli->error . PHP_EOL;
      exit(1);
    }
  }
}

// Initial caller
run_db_upgrade($db_version_now);
?>
