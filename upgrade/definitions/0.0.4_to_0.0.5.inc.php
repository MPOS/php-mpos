<?php
function run_005() {
  // Ugly but haven't found a better way
  global $setting;

  // Version information
  $db_version_old = '0.0.4';  // What version do we expect
  $db_version_new = '0.0.5';  // What is the new version we wish to upgrade to
  $db_version_now = $setting->getValue('DB_VERSION');  // Our actual version installed

  // Upgrade specific variables
  $aSql[] = "UPDATE `accounts` SET `coin_address` = NULL WHERE `coin_address` = ''";
  $aSql[] = "ALTER TABLE  `accounts` ADD UNIQUE INDEX (  `coin_address` )";
  $aSql[] = "INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.5') ON DUPLICATE KEY UPDATE `value` = '0.0.5'";

  if ($db_version_now == $db_version_old && version_compare($db_version_now, DB_VERSION, '<')) {
    execute_db_upgrade($aSql);
  }
}
?>
