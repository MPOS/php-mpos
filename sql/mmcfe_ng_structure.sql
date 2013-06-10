-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 09, 2013 at 04:08 PM
-- Server version: 5.5.31-0ubuntu0.13.04.1
-- PHP Version: 5.4.9-4ubuntu2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mmcfe_ng`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `admin` int(1) NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL COMMENT 'Assocaited email: used for validating users, and re-setting passwords',
  `loggedIp` varchar(255) DEFAULT NULL,
  `sessionTimeoutStamp` int(255) DEFAULT NULL,
  `pin` varchar(255) NOT NULL COMMENT 'four digit pin to allow account changes',
  `api_key` varchar(255) DEFAULT NULL,
  `token` varchar(65) DEFAULT NULL,
  `donate_percent` float DEFAULT '0',
  `ap_threshold` float DEFAULT '0',
  `coin_address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

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
  `shares` int(255) unsigned DEFAULT NULL,
  `share_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `height` (`height`,`blockhash`),
  KEY `time` (`time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Discovered blocks persisted from Litecoin Service';

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notification_settings`
--

CREATE TABLE IF NOT EXISTS `notification_settings` (
  `type` varchar(15) NOT NULL,
  `account_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pool_worker`
--

CREATE TABLE IF NOT EXISTS `pool_worker` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `account_id` int(255) NOT NULL,
  `username` char(50) DEFAULT NULL,
  `password` char(255) DEFAULT NULL,
  `monitor` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`name`),
  UNIQUE KEY `setting` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shares`
--

CREATE TABLE IF NOT EXISTS `shares` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `rem_host` varchar(255) NOT NULL,
  `username` varchar(120) NOT NULL,
  `our_result` enum('Y','N') NOT NULL,
  `upstream_result` enum('Y','N') DEFAULT NULL,
  `reason` varchar(50) DEFAULT NULL,
  `solution` varchar(257) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `upstream_result` (`upstream_result`),
  KEY `our_result` (`our_result`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shares_archive`
--

CREATE TABLE IF NOT EXISTS `shares_archive` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `share_id` int(255) unsigned NOT NULL,
  `username` varchar(120) NOT NULL,
  `our_result` enum('Y','N') DEFAULT NULL,
  `upstream_result` enum('Y','N') DEFAULT NULL,
  `block_id` int(10) unsigned NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `share_id` (`share_id`),
  KEY `time` (`time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Archive shares for potential later debugging purposes';

-- --------------------------------------------------------

--
-- Table structure for table `statistics_shares`
--

CREATE TABLE IF NOT EXISTS `statistics_shares` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(10) unsigned NOT NULL,
  `block_id` int(10) unsigned NOT NULL,
  `valid` int(11) NOT NULL,
  `invalid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `block_id` (`block_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `account_id` int(255) unsigned NOT NULL,
  `type` enum('Credit','Debit_MP','Debit_AP','Donation','Fee','Orphan_Credit','Orphan_Fee','Orphan_Donation','Credit_PPS','Fee_PPS','Donation_PPS') DEFAULT NULL,
  `coin_address` varchar(255) DEFAULT NULL,
  `amount` double DEFAULT '0',
  `block_id` int(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `block_id` (`block_id`),
  KEY `account_id` (`account_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
