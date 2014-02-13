CREATE INDEX `account_id_archived` ON `transactions` (`account_id`,`archived`);
INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.6') ON DUPLICATE KEY UPDATE `value` = '0.0.6';
