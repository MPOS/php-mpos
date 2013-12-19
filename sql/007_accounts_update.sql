ALTER TABLE  `accounts` ADD  `failed_pins` INT NOT NULL AFTER  `failed_logins` ;
ALTER TABLE  `accounts` ADD  `send_notices_to_inbox` TINYINT NOT NULL DEFAULT  '0';