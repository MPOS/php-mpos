<?php

class Security_CSRFToken extends PHPUnit_Framework_Testcase {
  /**
   * Tests if a CSRF token succeeds for a matching user and type
   */
  function testCSRFToken_success() {
    global $config;
    global $user;
    global $csrftoken;
    
    // no delay
    // TODO: simulate delay without a sleep ? test length
    $created_token = $csrftoken->getBasic($user->getCurrentIP(), 'test-token');
    $test_token = $csrftoken->checkBasic($user->getCurrentIP(), 'test-token', $created_token);
    $this->assertTrue($test_token);
  }
  
  /**
   * Tests if a CSRF token correctly fails
   */
  function testCSRFToken_fail() {
    global $config;
    global $user;
    global $csrftoken;
    
    // differing user
    $created_token = $csrftoken->getBasic('not the same', 'test-token');
    $test_token = $csrftoken->checkBasic($user->getCurrentIP(), 'test-token', $created_token);
    $this->assertFalse($test_token);
    
    // differing type
    $created_token2 = $csrftoken->getBasic($user->getCurrentIP(), 'not the same');
    $test_token2 = $csrftoken->checkBasic($user->getCurrentIP(), 'test-token', $created_token2);
    $this->assertFalse($test_token2);
    
    // token slightly shortened
    $created_token3 = $csrftoken->getBasic($user->getCurrentIP(), 'test-token');
    $created_token3 = substr($created_token3, 0, (strlen($created_token3)-1));
    $test_token3 = $csrftoken->checkBasic($user->getCurrentIP(), 'test-token', $created_token3);
    $this->assertFalse($test_token3);
  }
}

?>