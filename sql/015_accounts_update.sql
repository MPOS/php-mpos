ALTER TABLE `accounts` ADD COLUMN `gauth_key` VARCHAR( 65 ) NOT NULL DEFAULT '' AFTER `coin_address`;
ALTER TABLE `accounts` ADD COLUMN `gauth_enabled` INT( 1 ) NULL DEFAULT 0 AFTER `gauth_key`;
INSERT INTO `token_types` (`id`, `name`, `expiration`) VALUES (8, 'disable_gauth', 3600), (9, 'unlock_settings', 3600);

CREATE TABLE IF NOT EXISTS `user_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `account_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_account` (`name`,`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.5') ON DUPLICATE KEY UPDATE `value` = '0.0.5';
