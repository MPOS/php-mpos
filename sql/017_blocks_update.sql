ALTER TABLE  `blocks` CHANGE  `share_id`  `share_id` BIGINT( 30 ) NULL DEFAULT NULL ;
INSERT INTO `settings` (`name`, `value`) VALUES ('DB_VERSION', '0.0.7') ON DUPLICATE KEY UPDATE `value` = '0.0.7';
