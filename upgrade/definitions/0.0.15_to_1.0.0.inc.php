<?php
function run_100() {
  // Ugly but haven't found a better way
  global $setting, $config, $statistics, $block, $mysqli;

  // Version information
  $db_version_old = '0.0.15';  // What version do we expect
  $db_version_new = '1.0.0';  // What is the new version we wish to upgrade to
  $db_version_now = $setting->getValue('DB_VERSION');  // Our actual version installed

  // Upgrade specific variables
  $aSql[] = "ALTER TABLE `blocks` CHANGE `shares` `shares` BIGINT(30) unsigned DEFAULT NULL";
  $aSql[] = "UPDATE " . $setting->getTableName() . "    SET value = '1.0.0' WHERE name = 'DB_VERSION'";

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
