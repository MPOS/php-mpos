INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.1') ON DUPLICATE KEY UPDATE `value` = '0.0.1';
INSERT INTO `settings` (`name`, `value`) VALUES ('db_upgrade_required', 0) ON DUPLICATE KEY UPDATE `value` = 0;
