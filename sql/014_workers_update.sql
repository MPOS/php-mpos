ALTER TABLE  `pool_worker` ADD `manual_diff` float NOT NULL DEFAULT '0' AFTER  `monitor`;
INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.4') ON DUPLICATE KEY UPDATE `value` = '0.0.4';
INSERT INTO `settings` (`name`, `value`) VALUES ('db_upgrade_required', 0) ON DUPLICATE KEY UPDATE `value` = 0;
