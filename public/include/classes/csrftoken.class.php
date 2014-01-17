<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

class CSRFToken Extends Base {
  /**
   * Gets a basic CSRF token for this user/type and time chunk
   * @param string User; for hash seed, if username isn't available use IP
   * @param string Type of token; for hash seed, should be unique per page/use
   * @return string CSRF token
   */
  public function getBasic($user, $type) {
    $date = date('m/d/y/H/i/s');
    $data = explode('/', $date);
    $month = $data[0];    $day = $data[1];      $year = $data[2];
    $hour = $data[3];     $minute = $data[4];   $second = $data[5];
    $seed = $this->salty;
    $lead = $this->config['csrf']['options']['leadtime'];
    if ($lead >= 11) { $lead = 10; }
    if ($lead <= 0) { $lead = 3; }
    if ($minute == 59 && $second > (60-$lead)) {
      $minute = 0;
      $fhour = ($hour == 23) ? $hour = 0 : $hour+=1;
    }
    $seed = $seed.$month.$day.$user.$type.$year.$hour.$minute.$seed;
    return $this->getHash($seed);
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