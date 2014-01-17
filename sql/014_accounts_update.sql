ALTER TABLE  `accounts` ADD COLUMN `signup_timestamp` INT( 10 ) NOT NULL DEFAULT '0' AFTER `failed_pins`;
INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.4') ON DUPLICATE KEY UPDATE `value` = '0.0.4';
