<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

/**
 * Helper class for our cronjobs
 * Implements some common cron tasks outside
 * the scope of our web application
 **/
class Tools extends Base {
  /**
   * Fetch JSON data from an API
   * @param url string API URL
   * @param target string API method
   * @param auth array Optional authentication data to be sent with
   * @return dec array JSON decoded PHP array
   **/
  private function getApi($url, $target, $auth=NULL) {
    static $ch = null;
    static $ch = null;
    if (is_null($ch)) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
    }
    curl_setopt($ch, CURLOPT_URL, $url . $target);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    // run the query
    $res = curl_exec($ch);
    if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
    $dec = json_decode($res, true);
    if (!$dec) throw new Exception('Invalid data received, please make sure connection is working and requested API exists');
    return $dec;
  }

  /**
   * Detect the API to properly extract information
   * @param url string API URL
   * @return data string API type
   **/
  private function getApiType($url) {
    if (preg_match('/coinchoose.com/', $url)) {
      return 'coinchose';
    } else if (preg_match('/btc-e.com/', $url)) {
      return 'btce';
    } else if (preg_match('/cryptsy.com/', $url)) {
      return 'cryptsy';
    }
    $this->setErrorMessage("API URL unknown");
    return false;
  }

  /**
   * Extract price information from API data
   **/
  public function getPrice() {
    $aData = $this->getApi($this->config['price']['url'], $this->config['price']['target']);
    $strCurrency = $this->config['currency'];
    // Check the API type for configured URL
    if (!$strApiType = $this->getApiType($this->config['price']['url']))
      return false;
    // Extract price depending on API type
    switch ($strApiType) {
    case 'coinchose':
      foreach ($aData as $aItem) {
        if($strCurrency == $aItem[0])
          return $aItem['price'];
      }
      break;
    case 'btce':
      return $aData['ticker']['last'];
      break;
    case 'cryptsy':
      return $aData['return']['markets'][$strCurrency]['lasttradeprice'];
      break;
    }
    // Catchall, we have no data extractor for this API url
    $this->setErrorMessage("Undefined API to getPrice() on URL " . $this->config['price']['url']);
    return false;
  }
}

$tools = new Tools();
$tools->setDebug($debug);
$tools->setConfig($config);
