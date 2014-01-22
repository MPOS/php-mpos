ALTER TABLE `accounts` ADD COLUMN `gauth_key` VARCHAR( 65 ) NOT NULL DEFAULT '' AFTER `coin_address`;
ALTER TABLE `accounts` ADD COLUMN `gauth_enabled` INT( 1 ) NULL DEFAULT 0 AFTER `gauth_key`;
INSERT INTO `token_types` (`id`, `name`, `expiration`) VALUES ('8', 'disable_gauth', '3600');
INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.5') ON DUPLICATE KEY UPDATE `value` = '0.0.5';
