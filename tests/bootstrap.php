<?php 

error_reporting(E_ALL);

define('SECURITY', 'so we can check config options');

// pull in our tests config
require_once('config.php');

define('REALCONFIG', BASEPATH.'include/config/global.inc.php');
define('DISTCONFIG', BASEPATH.'include/config/global.inc.dist.php');

if (!DIST_OR_REAL_CONFIG) {
  require_once(DISTCONFIG);
} else {
  require_once(REALCONFIG);
}

require_once(BASEPATH . 'include/autoloader.inc.php');

require_once("PHPUnit/Autoload.php");

?>