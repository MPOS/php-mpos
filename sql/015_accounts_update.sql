UPDATE `accounts` SET `coin_address` = NULL WHERE `coin_address` = '';
ALTER TABLE  `accounts` ADD UNIQUE INDEX (  `coin_address` ) ;
INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.5') ON DUPLICATE KEY UPDATE `value` = '0.0.5';
