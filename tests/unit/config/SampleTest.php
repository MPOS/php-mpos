<?php

// these are ONLY here because we're checking config options
// these should NOT be in a normal unit test
define('SECURITY', 'so we can check config options');
define("BASEPATH", "./");

require_once(BASEPATH.'public/include/config/global.inc.dist.php');
require_once("PHPUnit/Autoload.php");

class TestDistConfig extends PHPUnit_Framework_Testcase {
  /**
* Test to make sure SALT is sane
*/
  function testSalt() {
    $this->assertNotEmpty(SALT);
    $this->assertGreaterThan(1, strlen(SALT));
  }
}

?>