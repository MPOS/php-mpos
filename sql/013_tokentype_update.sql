INSERT INTO `token_types` (`name`, `expiration`) VALUES ('account_edit', 360);
INSERT INTO `token_types` (`name`, `expiration`) VALUES ('change_pw', 360);
INSERT INTO `token_types` (`name`, `expiration`) VALUES ('withdraw_funds', 360);
CREATE INDEX `account_id` ON `notification_settings` (`account_id`);
CREATE UNIQUE INDEX `account_id_type` ON `notification_settings` (`account_id`,`type`);
INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.3') ON DUPLICATE KEY UPDATE `value` = '0.0.3';
