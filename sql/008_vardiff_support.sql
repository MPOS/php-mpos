ALTER TABLE  `shares` ADD  `difficulty` FLOAT NOT NULL AFTER  `solution` ;
ALTER TABLE  `shares_archive` ADD  `difficulty` FLOAT NOT NULL AFTER  `time` ;
ALTER TABLE  `pool_worker` ADD  `difficulty` FLOAT NOT NULL AFTER  `password` ;
