<?php 

// full path to mpos public directory, with trailing slash
// haven't been able to set this to __DIR__ + changing bootstrap to have the tests work, so full path seems best
define('BASEPATH', '/var/www/php-mpos-allbranches/php-mpos/public/');

// choose which config to test against, dist or real
// 0 = dist, 1 = real
define('DIST_OR_REAL_CONFIG', 1);

?>