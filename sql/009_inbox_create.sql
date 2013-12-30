CREATE TABLE IF NOT EXISTS `inbox` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id_to` int(11) unsigned NOT NULL,
  `account_id_from` int(11) unsigned NOT NULL,
  `subject` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `account_id_to` (`account_id_to`,`is_read`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
ALTER TABLE  `accounts` ADD  `send_notices_to_inbox` TINYINT NOT NULL DEFAULT  '0';