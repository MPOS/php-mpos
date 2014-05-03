<?php
function run_0010() {
  // Ugly but haven't found a better way
  global $setting, $config, $statistics, $block, $mysqli;

  // Version information
  $db_version_old = '0.0.9';  // What version do we expect
  $db_version_new = '0.0.10';  // What is the new version we wish to upgrade to
  $db_version_now = $setting->getValue('DB_VERSION');  // Our actual version installed

  // Upgrade specific variables
  $aSql[] = "UPDATE " . $setting->getTableName() . "    SET value = '0.0.10' WHERE name = 'DB_VERSION'";

  echo '- Starting configuration migration into new location' . PHP_EOL;
  $files = glob(BASEPATH . '../public/include/config/*');
  foreach ($files as $configFile) {
    echo '-   Moving ' . basename($configFile) . PHP_EOL;
    system('mv ' . $configFile . ' ../include/config/');
  }

  echo '- Starting folder cleanup' . PHP_EOL;
  $folders = array(BASEPATH . '../public/include/config', BASEPATH . '../public/include');
  foreach ($folders as $folderPath) {
    if (file_exists($folderPath)) {
      echo '-   Removing ' . $folderPath . PHP_EOL;
      $files = glob($folderPath . '/*');
      if (count($files) == 0) {
        system('rmdir ' . $folderPath);
      } else {
        echo '!     folder not empty, not removed' . PHP_EOL;
      }
    }
  }

  echo '! Use `git status` to clenaup any remaining left over folders' . PHP_EOL;

  if ($db_version_now == $db_version_old && version_compare($db_version_now, DB_VERSION, '<')) {
    // Run the upgrade
    echo '- Starting database migration to version ' . $db_version_new . PHP_EOL;
    foreach ($aSql as $sql) {
      echo '-  Preparing: ' . $sql . PHP_EOL;
      $stmt = $mysqli->prepare($sql);
      if ($stmt && $stmt->execute()) {
        echo '-    success' . PHP_EOL;
      } else {
        echo '!    failed: ' . $mysqli->error . PHP_EOL;
        exit(1);
      }
    }
  }
}
?>
