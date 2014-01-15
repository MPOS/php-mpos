INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.2') ON DUPLICATE KEY UPDATE `value` = '0.0.2';
INSERT INTO `settings` (`name`, `value`) VALUES ('db_upgrade_required', 0) ON DUPLICATE KEY UPDATE `value` = 0;
