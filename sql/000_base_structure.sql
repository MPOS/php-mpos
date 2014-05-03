SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `no_fees` tinyint(1) NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL COMMENT 'Assocaited email: used for validating users, and re-setting passwords',
  `timezone` varchar(35) NOT NULL DEFAULT '415',
  `notify_email` VARCHAR( 255 ) NULL DEFAULT NULL,
  `loggedIp` varchar(255) DEFAULT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `failed_logins` int(5) unsigned DEFAULT '0',
  `failed_pins` int(5) unsigned DEFAULT '0',
  `signup_timestamp` int(10) DEFAULT '0',
  `last_login` int(10) DEFAULT NULL,
  `pin` varchar(255) NOT NULL COMMENT 'four digit pin to allow account changes',
  `api_key` varchar(255) DEFAULT NULL,
  `token` varchar(65) DEFAULT NULL,
  `donate_percent` float DEFAULT '0',
  `ap_threshold` float DEFAULT '0',
  `coin_address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `coin_address` (`coin_address`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `height` int(10) unsigned NOT NULL,
  `blockhash` char(65) NOT NULL,
  `confirmations` int(10) NOT NULL,
  `amount` double NOT NULL,
  `difficulty` double NOT NULL,
  `time` int(11) NOT NULL,
  `accounted` tinyint(1) NOT NULL DEFAULT '0',
  `account_id` int(255) unsigned DEFAULT NULL,
  `worker_name` varchar(50) DEFAULT 'unknown',
  `shares` int(255) unsigned DEFAULT NULL,
  `share_id` bigint(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `height` (`height`,`blockhash`),
  KEY `time` (`time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Discovered blocks persisted from Litecoin Service';

CREATE TABLE IF NOT EXISTS `invitations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) unsigned NOT NULL,
  `email` varchar(50) NOT NULL,
  `token_id` int(11) NOT NULL,
  `is_activated` tinyint(1) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `monitoring` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `type` varchar(15) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Monitoring events from cronjobs';

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(10) unsigned NOT NULL,
  `header` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(25) NOT NULL,
  `data` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `account_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `data` (`data`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `notification_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(15) NOT NULL,
  `account_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  UNIQUE KEY `account_id_type` (`account_id`,`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `payouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`,`completed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pool_worker` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `account_id` int(255) NOT NULL,
  `username` char(50) DEFAULT NULL,
  `password` char(255) DEFAULT NULL,
  `difficulty` float NOT NULL DEFAULT '0',
  `monitor` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `account_id` (`account_id`),
  KEY `pool_worker_username` (`username`(10))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  PRIMARY KEY (`name`),
  UNIQUE KEY `setting` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.10');

CREATE TABLE IF NOT EXISTS `shares` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `rem_host` varchar(255) NOT NULL,
  `username` varchar(120) NOT NULL,
  `our_result` enum('Y','N') NOT NULL,
  `upstream_result` enum('Y','N') DEFAULT NULL,
  `reason` varchar(50) DEFAULT NULL,
  `solution` varchar(257) NOT NULL,
  `difficulty` float NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `upstream_result` (`upstream_result`),
  KEY `our_result` (`our_result`),
  KEY `username` (`username`),
  KEY `shares_username` (`username`(10))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `shares_archive` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `share_id` int(255) unsigned NOT NULL,
  `username` varchar(120) NOT NULL,
  `our_result` enum('Y','N') DEFAULT NULL,
  `upstream_result` enum('Y','N') DEFAULT NULL,
  `block_id` int(10) unsigned NOT NULL,
  `difficulty` float NOT NULL DEFAULT '0',
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `share_id` (`share_id`),
  KEY `time` (`time`),
  KEY `our_result` (`our_result`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Archive shares for potential later debugging purposes';

CREATE TABLE IF NOT EXISTS `statistics_shares` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(10) unsigned NOT NULL,
  `block_id` int(10) unsigned NOT NULL,
  `valid` int(11) NOT NULL,
  `invalid` int(11) NOT NULL DEFAULT '0',
  `pplns_valid` int(11) NOT NULL,
  `pplns_invalid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `block_id` (`block_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `token` varchar(65) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `token_types` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `expiration` INT NULL DEFAULT  '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `token_types` (`id`, `name`, `expiration`) VALUES
(1, 'password_reset', 3600),
(2, 'confirm_email', 0),
(3, 'invitation', 0),
(4, 'account_unlock', 0),
(5, 'account_edit', 3600),
(6, 'change_pw', 3600),
(7, 'withdraw_funds', 3600);

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `account_id` int(255) unsigned NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `coin_address` varchar(255) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `block_id` int(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `txid` varchar(256) DEFAULT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `block_id` (`block_id`),
  KEY `account_id` (`account_id`),
  KEY `type` (`type`),
  KEY `archived` (`archived`),
  KEY `account_id_archived` (`account_id`,`archived`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `templates` (
  `template` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `content` mediumtext,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`template`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

