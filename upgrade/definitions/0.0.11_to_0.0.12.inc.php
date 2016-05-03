<?php
function run_0012() {
  // Ugly but haven't found a better way
  global $setting, $config, $user, $mysqli;

  // Version information
  $db_version_old = '0.0.11';  // What version do we expect
  $db_version_new = '0.0.12';  // What is the new version we wish to upgrade to
  $db_version_now = $setting->getValue('DB_VERSION');  // Our actual version installed

  // Upgrade specific variables
  $aSql[] = "CREATE TABLE `coin_addresses` ( `id` int(11) NOT NULL AUTO_INCREMENT, `account_id` int(11) NOT NULL, `currency` varchar(5) NOT NULL, `coin_address` varchar(255) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `coin_address` (`coin_address`), KEY `account_id` (`account_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
  $aSql[] = "INSERT IGNORE INTO coin_addresses (account_id, currency, coin_address) SELECT id, '" . $config['currency'] . "', coin_address FROM " . $user->getTableName() . " WHERE coin_address IS NOT NULL";
  $aSql[] = "ALTER TABLE " . $user->getTableName() . " DROP `coin_address`";
  $aSql[] = "UPDATE " . $setting->getTableName() . "    SET value = '0.0.12' WHERE name = 'DB_VERSION'";

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
