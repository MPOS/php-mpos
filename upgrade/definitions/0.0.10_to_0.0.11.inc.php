<?php
function run_0011() {
  // Ugly but haven't found a better way
  global $setting, $config, $user, $mysqli;

  // Version information
  $db_version_old = '0.0.10';  // What version do we expect
  $db_version_new = '0.0.11';  // What is the new version we wish to upgrade to
  $db_version_now = $setting->getValue('DB_VERSION');  // Our actual version installed

  // Upgrade specific variables
  $aSql[] = "ALTER TABLE `shares_archive` CHANGE  `id` `id` BIGINT(30) unsigned NOT NULL AUTO_INCREMENT";
  $aSql[] = "ALTER TABLE `shares_archive` CHANGE `share_id` `share_id` BIGINT(30) unsigned NOT NULL";
  $aSql[] = "UPDATE " . $setting->getTableName() . "    SET value = '0.0.11' WHERE name = 'DB_VERSION'";

  if ($db_version_now == $db_version_old && version_compare($db_version_now, DB_VERSION, '<')) {
    // Run the upgrade
    echo '- Starting database migration to version ' . $db_version_new . PHP_EOL;
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
}
?>