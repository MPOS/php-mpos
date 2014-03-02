<?php

class Security_Tokens extends PHPUnit_Framework_Testcase {
  /**
   * Tests tokens CRUD
   */
  function testTokens_CRUD() {
    global $config;
    global $mysqli;
    $mysqli = new DBConnection($config);
    global $tokentype;
    global $oToken;
    // grab token types first so we can test them all
    $token_types = $tokentype->getAll();
    
    foreach ($token_types as $tt) {
      // create
      $create_token = $oToken->createToken($tt['name'], 1);
      $this->assertStringMatchesFormat('%x', $create_token);
      $this->assertGreaterThan(16, strlen($create_token));
    }
  }
}

?>