<?php
function run_004() {
  // Ugly but haven't found a better way
  global $setting;

  // Version information
  $db_version_old = '0.0.3';  // What version do we expect
  $db_version_new = '0.0.4';  // What is the new version we wish to upgrade to
  $db_version_now = $setting->getValue('DB_VERSION');  // Our actual version installed

  // Upgrade specific variables
  $aSql[] = "ALTER TABLE `accounts` ADD COLUMN `signup_timestamp` INT( 10 ) NOT NULL DEFAULT '0' AFTER `failed_pins`";
  $aSql[] = "ALTER TABLE `accounts` ADD COLUMN `notify_email` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `email`";
  $aSql[] = "TRUNCATE TABLE `token_types`";
  $aSql[] = "INSERT INTO `token_types` (`id`, `name`, `expiration`) VALUES (1, 'password_reset', 3600), (2, 'confirm_email', 0), (3, 'invitation', 0), (4, 'account_unlock', 0), (5, 'account_edit', 3600), (6, 'change_pw', 3600), (7, 'withdraw_funds', 3600)";
  $aSql[] = "INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.4') ON DUPLICATE KEY UPDATE `value` = '0.0.4'";

  if ($db_version_now == $db_version_old && version_compare($db_version_now, DB_VERSION, '<')) {
    execute_db_upgrade($aSql);
  }
}
?>
