<?php
function run_103() {
  // Ugly but haven't found a better way
  global $setting, $config, $coin_address, $user, $mysqli;

  // Version information
  $db_version_old = '1.0.2';  // What version do we expect
  $db_version_new = '1.0.3';  // What is the new version we wish to upgrade to
  $db_version_now = $setting->getValue('DB_VERSION');  // Our actual version installed

  // Upgrade specific variables
  $aSql[] = "ALTER TABLE `blocks` CHANGE `shares` `shares` DOUBLE UNSIGNED DEFAULT NULL;";
  $aSql[] = "UPDATE `statistics_shares` SET `valid` = '0' WHERE `valid` IS NULL;";
  $aSql[] = "UPDATE `statistics_shares` SET `pplns_valid` = '0' WHERE `pplns_valid` IS NULL;";
  $aSql[] = "ALTER TABLE `statistics_shares` CHANGE `valid` `valid` FLOAT UNSIGNED NOT NULL DEFAULT '0', CHANGE `invalid` `invalid` FLOAT UNSIGNED NOT NULL DEFAULT '0', CHANGE `pplns_valid` `pplns_valid` FLOAT UNSIGNED NOT NULL DEFAULT '0', CHANGE `pplns_invalid` `pplns_invalid` FLOAT UNSIGNED NOT NULL DEFAULT '0';";
  $aSql[] = "UPDATE " . $setting->getTableName() . " SET value = '" . $db_version_new . "' WHERE name = 'DB_VERSION';";

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
