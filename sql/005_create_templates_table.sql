CREATE TABLE `templates` (
  `template` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `content` mediumtext,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`template`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
