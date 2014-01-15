INSERT INTO `token_types` (`name`, `expiration`) VALUES ('account_edit', 360);
INSERT INTO `token_types` (`name`, `expiration`) VALUES ('change_pw', 360);
INSERT INTO `token_types` (`name`, `expiration`) VALUES ('withdraw_funds', 360);
INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.3') ON DUPLICATE KEY UPDATE `value` = '0.0.3';
INSERT INTO `settings` (`name`, `value`) VALUES ('db_upgrade_required', 0) ON DUPLICATE KEY UPDATE `value` = 0;
