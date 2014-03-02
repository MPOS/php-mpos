<?php

class Security_Sessions extends PHPUnit_Framework_Testcase {
  /**
   * Tests if our current session checking will throw errors or take a malformed id
   */
  function testSessions_destruction_malformed_id() {
    global $config;
    
    $malformed_ids = array(
        "",
        "'",
        "9881o1ke7ia4k5*p1k28e6utg0"
    );
    
    foreach ($malformed_ids as $mid) {
      session_set_cookie_params(time()+$config['cookie']['duration'], $config['cookie']['path'], $config['cookie']['domain'], $config['cookie']['secure'], $config['cookie']['httponly']);
      $session_start = @session_start();
      if (!$session_start) {
        session_destroy();
        session_regenerate_id(true);
        session_start();
      }
      @setcookie(session_name(), session_id(), time()+$config['cookie']['duration'], $config['cookie']['path'], $config['cookie']['domain'], $config['cookie']['secure'], $config['cookie']['httponly']);
      $this->assertNotEquals($mid, session_id());
    }
  }
}

?>