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

/*
 * apache2* libapache2-mod-php5* mysql-server* php-codecoverage*
  php-codesniffer* php-file-iterator* php-gettext* php-pear* php-symfony-yaml*
  php-text-template* php-timer* php-token-stream* php5* php5-cli*
  php5-mysqlnd* phpmyadmin* phppgadmin* phpunit* phpunit-mock-object*
  sudo apt-get install mysql-server apache2 memcached php5-memcached php5-mysqlnd php5-curl php5-json php5-cli libapache2-mod-php5 phpmyadmin phpunit phpunit-mock-object
 */

// your db connection setup
class DBConnection {
  public function __construct($config) {
    return new mysqli($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['name'], $config['db']['port']);
  }
}

?>