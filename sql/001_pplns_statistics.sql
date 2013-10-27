ALTER TABLE  `statistics_shares` ADD `pplns_valid` int(11) NOT NULL AFTER  `invalid` ;
ALTER TABLE  `statistics_shares` ADD `pplns_invalid` int(11) NOT NULL DEFAULT 0 AFTER  `pplns_valid` ;
