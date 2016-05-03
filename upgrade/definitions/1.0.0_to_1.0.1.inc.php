<?php
function run_101() {
  // Ugly but haven't found a better way
  global $setting, $config, $coin_address, $user, $mysqli;

  // Version information
  $db_version_old = '1.0.0';  // What version do we expect
  $db_version_new = '1.0.1';  // What is the new version we wish to upgrade to
  $db_version_now = $setting->getValue('DB_VERSION');  // Our actual version installed

  // Upgrade specific variables
  $aSql[] = "ALTER TABLE " . $coin_address->getTableName() . " ADD ap_threshold float DEFAULT '0'";
  $aSql[] = "UPDATE " . $coin_address->getTableName() . " AS ca LEFT JOIN " . $user->getTableName() . " AS a ON a.id = ca.account_id SET ca.ap_threshold = a.ap_threshold";
  $aSql[] = "ALTER TABLE " . $user->getTableName() . " DROP `ap_threshold`";
  $aSql[] = "UPDATE " . $setting->getTableName() . "    SET value = '1.0.1' WHERE name = 'DB_VERSION'";

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
