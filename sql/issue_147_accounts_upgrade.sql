ALTER TABLE  `accounts` ADD  `is_locked` BOOLEAN NOT NULL DEFAULT FALSE AFTER  `email` ;
ALTER TABLE  `accounts` CHANGE  `admin`  `is_admin` BOOLEAN NOT NULL DEFAULT FALSE ;
