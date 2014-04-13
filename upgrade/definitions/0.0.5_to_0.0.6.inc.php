<?php
function run_006() {
  // Ugly but haven't found a better way
  global $setting;

  // Version information
  $db_version_old = '0.0.5';  // What version do we expect
  $db_version_new = '0.0.6';  // What is the new version we wish to upgrade to
  $db_version_now = $setting->getValue('DB_VERSION');  // Our actual version installed

  // Upgrade specific variables
  $aSql[] = "CREATE INDEX `account_id_archived` ON `transactions` (`account_id`,`archived`)";
  $aSql[] = "INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.6') ON DUPLICATE KEY UPDATE `value` = '0.0.6'";

  if ($db_version_now == $db_version_old && version_compare($db_version_now, DB_VERSION, '<')) {
    execute_db_upgrade($aSql);
  }
}
?>
