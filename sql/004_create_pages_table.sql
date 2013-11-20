CREATE TABLE `pages` (
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `page_templates` (
  `slug` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `content` mediumtext,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`slug`, `template`),
  KEY (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `pages` (`name`, `slug`) VALUES ('Support','support'), ('Getting Started','gettingstarted');
INSERT INTO `page_templates` (`slug`, `template`) VALUES ('support', NULL), ('support', 'mpos'), ('support', 'mmcFE'), ('support', 'mobile'), ('gettingstarted', NULL), ('gettingstarted', 'mpos'), ('gettingstarted', 'mmcFE'), ('gettingstarted', 'mobile');
