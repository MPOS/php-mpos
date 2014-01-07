ALTER TABLE `token_types` ADD  `expiration` INT NULL DEFAULT  '0';
UPDATE `token_types` SET `expiration` = 3600 WHERE `id` = 1;
