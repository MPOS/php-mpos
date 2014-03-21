<?php
function run_003() {
  // Ugly but haven't found a better way
  global $setting;

  // Version information
  $db_version_old = '0.0.2';  // What version do we expect
  $db_version_new = '0.0.3';  // What is the new version we wish to upgrade to
  $db_version_now = $setting->getValue('DB_VERSION');  // Our actual version installed

  // Upgrade specific variables
  $aSql[] = "INSERT INTO `token_types` (`name`, `expiration`) VALUES ('account_edit', 360)";
  $aSql[] = "INSERT INTO `token_types` (`name`, `expiration`) VALUES ('change_pw', 360)";
  $aSql[] = "INSERT INTO `token_types` (`name`, `expiration`) VALUES ('withdraw_funds', 360)";
  $aSql[] = "CREATE INDEX `account_id` ON `notification_settings` (`account_id`)";
  $aSql[] = "CREATE UNIQUE INDEX `account_id_type` ON `notification_settings` (`account_id`,`type`)";
  $aSql[] = "INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.3') ON DUPLICATE KEY UPDATE `value` = '0.0.3'";

  if ($db_version_now == $db_version_old && version_compare($db_version_now, DB_VERSION, '<')) {
    execute_db_upgrade($aSql);
  }
}
?>
