<?php 
error_reporting(E_ALL);

class SessionManager {
  private $session_state = 0;
  
  public function create_session($ip) {
    // TODO: put memcache rate limiting into here
  }
}

class mysqli_strict extends mysqli {
  public function bind_param($paramTypes) {
    if (!is_string($paramTypes)) {
      return false;
    } else {
      $args = func_get_args();
      $acopy = $args;
      $nargs = count($args);
      for($i=1;$i<$nargs;$i++) {
        $pos = substr($paramTypes, ($i-1), 1);
        switch ($pos) {
        	case 's':
        	  $return_str = filter_var($acopy[$i], FILTER_VALIDATE_STRING, FILTER_NULL_ON_FAILURE);
        	  return ($return_str !== null) ? (string)$return_str : false;
        	  break;
        	case 'i':
        	  $return_int = filter_var($acopy[$i], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        	  return ($return_int !== null) ? (int)$return_int : false;
        	  break;
        	case 'd':
        	  $return_dbl = filter_var($acopy[$i], FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        	  return ($return_dbl !== null) ? (double)$return_dbl : false;
        	  break;
        	case 'b':
        	  $return_bool = filter_var($acopy[$i], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        	  return ($return_bool !== null) ? (boolean)$return_bool : false;
        	  break;
        }
      }
    }
  }
}

?>