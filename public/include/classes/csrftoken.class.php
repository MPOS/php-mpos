<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

class CSRFToken Extends Base {
  /**
   * Gets a basic CSRF token for this user/type and time chunk
   * @param string user User; for hash seed, if username isn't available use IP
   * @param string type Type of token; for hash seed, should be unique per page/use
   * @param string timing Which date() chars we add to the seed; default month day year hour minute ie same minute only
   * @param string seedExtra Extra information to add to the seed
   * @return string CSRF token
   */
  public function getBasic($user, $type, $timing='mdyHi', $seedExtra='') {
    $date = date('m/d/y/H/i/s');
    $data = explode('/', $date);
    $month = $data[0];    $day = $data[1];        $year = $data[2];
    $hour = $data[3];     $minute = $data[4];     $second = $data[5];
    $salt1 = $this->salt; $salt2 = $this->salty;  $seed = $salt1;
    $lead = $this->config['csrf']['options']['leadtime'];
    if ($lead >= 11) { $lead = 10; }
    if ($lead <= 0) { $lead = 3; }
    if ($minute == 59 && $second > (60-$lead)) {
      $minute = 0;
      $fhour = ($hour == 23) ? $hour = 0 : $hour+=1;
    }
    $seed.= (strpos($timing, 'm') !== false) ? $month : '';
    $seed.= (strpos($timing, 'd') !== false) ? $day : '';
    $seed.= (strpos($timing, 'y') !== false) ? $year : '';
    $seed.= (strpos($timing, 'H') !== false) ? $hour : '';
    $seed.= (strpos($timing, 'i') !== false) ? $minute : '';
    $seed.= (strpos($timing, 's') !== false) ? $second : '';
    $seed.= ($seedExtra !== '') ? $seedExtra.$salt2 : $salt2;
    return $this->getHash($seed);
  }
  
  /**
   * 
   * @param unknown $dowhat
   * @return string
   */
  public static function getDescriptionImageHTML($dowhat="try") {
    $string = "<img src='site_assets/mpos/images/questionmark.png' ";
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
$csrftoken->setSalt(SALT);
$csrftoken->setSalty(SALTY);
$csrftoken->setMail($mail);
$csrftoken->setUser($user);
$csrftoken->setToken($oToken);
$csrftoken->setConfig($config);
$csrftoken->setErrorCodes($aErrorCodes);
?>