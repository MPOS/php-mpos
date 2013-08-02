ALTER TABLE  `shares` ADD  `difficulty` FLOAT NOT NULL AFTER  `solution` ;
ALTER TABLE  `pool_worker` ADD  `difficulty` FLOAT NOT NULL AFTER  `password` ;
