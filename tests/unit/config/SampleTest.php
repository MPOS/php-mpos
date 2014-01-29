<?php

// these are ONLY here because we're checking config options
// these should NOT be in a normal unit test

class TestDistConfig extends PHPUnit_Framework_Testcase {
  /**
   * Test to make sure SALT is sane
   */
  function testSaltLength() {
    $this->assertNotEmpty($config['SALT']);
    $this->assertGreaterThan(1, strlen($config['SALTY']));
  }
}

?>