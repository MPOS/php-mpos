ALTER TABLE shares_archive MODIFY id bigint(30);
ALTER TABLE shares_archive MODIFY share_id bigint(30);
UPDATE TABLE settings SET value = '0.0.11' WHERE name = 'DB_VERSION';
