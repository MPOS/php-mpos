ALTER TABLE  `transactions` ADD  `archived` BOOLEAN NOT NULL DEFAULT FALSE AFTER  `timestamp` ;
ALTER TABLE  `transactions` ADD INDEX (  `archived` ) ;
