<?php 
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class mysqli_strict extends mysqli {
  public function bind_param($paramTypes) {
    if (!is_string($paramTypes)) {
      return false;
    } else {
      $args = func_get_args();
      $acopy = $args;
      $nargs = count($args);
      for($i=1;$i<$nargs;$i++) {
        $ipos = ($i-1);
        $pos = substr($paramTypes, $ipos, 1);
        switch ($pos) {
        	case 's':
        	  $return_str = filter_var($acopy[$i], FILTER_VALIDATE_STRING, FILTER_NULL_ON_FAILURE);
        	  $acopy[$i] = ($return_str !== null) ? (string)$return_str : null;
        	  break;
        	case 'i':
        	  $return_int = filter_var($acopy[$i], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        	  $acopy[$i] = ($return_int !== null) ? (int)$return_int : null;
        	  break;
        	case 'd':
        	  $return_dbl = filter_var($acopy[$i], FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        	  $acopy[$i] = ($return_dbl !== null) ? (float)$return_dbl : null;
        	  break;
        	case 'b':
        	  $return_bool = filter_var($acopy[$i], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        	  $acopy[$i] = ($return_bool !== null) ? (bool)$return_bool : null;
        	  break;
        }
      }
      return (in_array(null, $acopy));
    }
  }
}

?>