<?php
function run_102() {
  // Ugly but haven't found a better way
  global $setting, $config, $coin_address, $user, $mysqli;

  // Version information
  $db_version_old = '1.0.1';  // What version do we expect
  $db_version_new = '1.0.2';  // What is the new version we wish to upgrade to
  $db_version_now = $setting->getValue('DB_VERSION');  // Our actual version installed

  // Upgrade specific variables
  $aSql[] = "
		CREATE TABLE IF NOT EXISTS `user_settings` (
		  `account_id` int(11) NOT NULL,
		  `name` varchar(50) NOT NULL,
		  `value` text DEFAULT NULL,
		  PRIMARY KEY (`account_id`,`name`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
  ";
  $aSql[] = "UPDATE " . $setting->getTableName() . "    SET value = '".$db_version_new."' WHERE name = 'DB_VERSION'";
  
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
