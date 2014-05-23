<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class CSRFToken Extends Base {
  public $valid = 0;
  /**
   * Gets a basic csrf token
   * @param string $user user or IP/host address
   * @param string $type page name or other unique per-page identifier
   */
  public function getBasic($user, $type) {
    $date = date('m/d/y/H/i');
    $d = explode('/', $date);
    $seed = $this->buildSeed($user.$type, $d[0], $d[1], $d[2], $d[3], $d[4]);
    return $this->getHash($seed);
  }
  
  /**
   * Returns +1 min and +1 hour rollovers hashes
   * @param string $user user or IP/host address
   * @param string $type page name or other unique per-page identifier
   * @return array 1min and 1hour hashes
   */
  public function checkAdditional($user, $type) {
    $date = date('m/d/y/H/i');
    $d = explode('/', $date);
    // minute may have rolled over
    $seed1 = $this->buildSeed($user.$type, $d[0], $d[1], $d[2], $d[3], ($d[4]-1));
    // hour may have rolled over
    $seed2 = $this->buildSeed($user.$type, $d[0], $d[1], $d[2], ($d[3]-1), 59);
    return array($this->getHash($seed1), $this->getHash($seed2));
  }
  
  /**
   * Builds a seed with the given data
   * @param string $data
   * @param int $year
   * @param int $month
   * @param int $day
   * @param int $hour
   * @param int $minute
   * @return string seed
   */
  private function buildSeed($data, $year, $month, $day, $hour, $minute) {
    return $this->salty.$year.$month.$day.$data.$hour.$minute.$this->salt;
  }
  
  /**
   * Checks if the token is correct as is, if not checks for rollovers with checkAdditional()
   * @param string $user user or IP/host address
   * @param string $type page name or other unique per-page identifier
   * @param string $token token to check against
   * @return boolean
   */
  public function checkBasic($user, $type, $token) {
    if (empty($token)) return false;
    $token_now = $this->getBasic($user, $type);
    if ($token_now !== $token) {
      $tokens_check = $this->checkAdditional($user, $type);
      $match = 0;
      foreach ($tokens_check as $checkit) {
        if ($checkit == $token) $match = 1;
      }
      return ($match) ? true : false;
    } else {
      return true;
    }
  }
  
  /**
   * Convenience method to get a token expired message with a token type, and ? image with description
   * @param string $tokentype if you want a specific tokentype, set it here
   * @param string $dowhat What will be put in the string "Simply $dowhat again to...", default is try
   */
  public static function getErrorWithDescriptionHTML($tokentype="", $dowhat="try") {
    return ($tokentype !== "") ? "$tokentype token expired, please try again ".self::getDescriptionImageHTML($dowhat) : "Token expired, please try again ".self::getDescriptionImageHTML($dowhat);
  }
  
  /**
   * Gets the HTML image (?) with short csrf description for users for the incorrect token error message
   * @param dowhat string What will be put in the string "Simply $dowhat again to...", default is try
   * @return string HTML image with description
   */
  public static function getDescriptionImageHTML($dowhat="try") {
    $string = "<img src='site_assets/bootstrap/images/questionmark.png' ";
    $string.= "title='Tokens are used to help us mitigate attacks; Simply ";
    $string.= htmlentities(strip_tags($dowhat));
    $string.= " again to continue' width='20px' height='20px'>";
    return $string;
  }
  
  private function getHash($string) {
    return hash('sha256', $this->salty.$string.$this->salt);
  }
}

$csrftoken = new CSRFToken();
$csrftoken->setDebug($debug);
$csrftoken->setMysql($mysqli);
$csrftoken->setSalt($config['SALT']);
$csrftoken->setSalty($config['SALTY']);
$csrftoken->setMail($mail);
$csrftoken->setUser($user);
$csrftoken->setToken($oToken);
$csrftoken->setConfig($config);
$csrftoken->setErrorCodes($aErrorCodes);
?>
