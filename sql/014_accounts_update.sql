ALTER TABLE `accounts` ADD COLUMN `signup_timestamp` INT( 10 ) NOT NULL DEFAULT '0' AFTER `failed_pins`;
ALTER TABLE `accounts` ADD COLUMN `notify_email` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `email`;
TRUNCATE TABLE `token_types`;
INSERT INTO `token_types` (`id`, `name`, `expiration`) VALUES (1, 'password_reset', 3600), (2, 'confirm_email', 0), (3, 'invitation', 0), (4, 'account_unlock', 0), (5, 'account_edit', 3600), (6, 'change_pw', 3600), (7, 'withdraw_funds', 3600);
INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.4') ON DUPLICATE KEY UPDATE `value` = '0.0.4';
