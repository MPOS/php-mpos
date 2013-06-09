ALTER TABLE  `notifications` ADD  `account_id` INT UNSIGNED NULL DEFAULT NULL , ADD INDEX (  `account_id` )
CREATE TABLE IF NOT EXISTS `notification_settings` (
  `type` varchar(15) NOT NULL,
  `account_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
