<?php 

// full path to mpos public directory, with trailing slash
define('BASEPATH', '/var/www/php-mpos-allbranches/php-mpos/public/');

// choose which config to test against, dist or real
// 0 = dist, 1 = real
define('DIST_OR_REAL_CONFIG', 1);

// because php cli defaults are shit, the socket might be wrong
ini_set('mysqli.default_socket', '/var/run/mysqld/mysqld.sock');

?>