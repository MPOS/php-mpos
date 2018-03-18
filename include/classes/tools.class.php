<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

/**
 * Helper class for our cronjobs
 * Implements some common cron tasks outside
 * the scope of our web application
 **/
class Tools extends Base {
  public function getOnlineVersions() {
    // Fetch version online, cache for a bit
    $key = $this->config['memcache']['keyprefix'] . 'ONLINE_VERSIONS';
    if (! $mpos_versions = $this->memcache->get($key)) {
      $url = $this->config['version_url'];
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_HEADER, false);
      $data = curl_exec($curl);
      preg_match('/define\(\'MPOS_VERSION\', \'(.*)\'\);/', $data, $match);
      $mpos_versions['MPOS_VERSION'] = @$match[1];
      preg_match('/define\(\'DB_VERSION\', \'(.*)\'\);/', $data, $match);
      $mpos_versions['DB_VERSION'] = @$match[1];
      preg_match('/define\(\'CONFIG_VERSION\', \'(.*)\'\);/', $data, $match);
      $mpos_versions['CONFIG_VERSION'] = @$match[1];
      curl_close($curl);
      return $this->memcache->setCache($key, $mpos_versions, 30);
    } else {
      return $mpos_versions;
    }
  }
  /**
   * Fetch JSON data from an API
   * @param url string API URL
   * @param target string API method
   * @param auth array Optional authentication data to be sent with
   * @return dec array JSON decoded PHP array
   **/
  public function getApi($url, $target, $auth=NULL) {
    static $ch = null;
    static $ch = null;
    if (is_null($ch)) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
    }
    
    $url = rtrim($url, '/');
    $target = ltrim($target, '/');
    curl_setopt($ch, CURLOPT_URL, $url . '/' . $target);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    // run the query
    $res = curl_exec($ch);
    if ($res === false) {
      $this->setErrorMessage('Could not get reply: '.curl_error($ch));
      return false;
    }
    $dec = json_decode($res, true);
    if (!$dec) {
      $this->setErrorMessage('Invalid data received, please make sure connection is working and requested API exists');
      return false;
    }
    return $dec;
  }

  /**
   * Detect the API to properly extract information
   * @param url string API URL
   * @return data string API type
   **/
  private function getApiType($url) {
    if (preg_match('/coinchoose.com/', $url)) {
      return 'coinchoose';
    } else if (preg_match('/btc-e.nz/', $url)) {
      return 'btce';
    } else if (preg_match('/cryptopia.co.nz/', $url)) {
     return 'cryptopia';
    } else if (preg_match('/cryptorush.in/', $url)) {
      return 'cryptorush';
    } else if (preg_match('/mintpal.com/', $url)) {
      return 'mintpal';
    } else if (preg_match('/c-cex.com/', $url)) {
      return 'c-cex';
    } else if (preg_match('/bittrex.com/', $url)) {
      return 'bittrex';
    } else if (preg_match('/crypto-bridge.org/', $url)) {
      return 'cryptobridge';
    } else if (preg_match('/yobit.net/', $url)) {
      return 'yobit';
    } else if (preg_match('/binance.com/', $url)) {
      return 'binance';
    } else if (preg_match('/southxchange.com/', $url)) {
      return 'southxchange';
    } else if (preg_match('/mercatox.com/', $url)) {
      return 'mercatox';
    } else if (preg_match('/tradeogre.com/', $url)) {
      return 'tradeogre';
    }
    $this->setErrorMessage("API URL unknown");
    return false;
  }

  /**
   * Extract price information from API data
   **/
  public function getPrice() {
    $aData = $this->getApi($this->config['price']['url'], $this->config['price']['target']);
    $strBase = $this->config['currency'];
    $strQuote = $this->config['price']['currency'];
    // Check the API type for configured URL
    if (!$strApiType = $this->getApiType($this->config['price']['url']))
      return false;
    // if api data is valid, extract price depending on API type
    if (is_array($aData)) {
      switch ($strApiType) {
      	case 'coinchoose':
      	  foreach ($aData as $aItem) {
      	    if($strBase == $aItem[0])
      	      return $aItem['price'];
      	  }
      	  break;
      	case 'btce':
      	  return $aData['ticker']['last'];
      	  break;
        case 'cryptopia':
      	  return @$aData['Data']['LastPrice'];
      	  break;
      	case 'cryptorush':
      	  return @$aData["{$strBase}/{$strQuote}"]['last_trade'];
      	  break;
      	case 'mintpal':
      	  return @$aData['0']['last_price'];
      	  break;
        case 'c-cex':
          return @$aData['ticker']['lastprice'];
          break;
      	case 'bittrex':
      	  return @$aData['result']['Last'];
      	  break;
        case 'cryptobridge':
          foreach ($aData as $aItem) {
            if("{$strBase}_{$strQuote}" == $aItem['id'])
              return $aItem['last'];
          }
        case 'yobit':
          return @$aData[strtolower($strBase) . "_" . strtolower($strQuote)]['last'];
          break;
        case 'binance':
          return @$aData['price'];
          break;
        case 'southxchange':
          return @$aData['Last'];
          break;
        case 'mercatox':
          return @$aData['pairs']["{$strBase}_{$strQuote}"]['last'];
          break;
        case 'tradeogre':
          return @$aData['price'];
          break;
      }
    } else {
      $this->setErrorMessage("Got an invalid response from ticker API");
      return false;
    }
    // Catchall, we have no data extractor for this API url
    $this->setErrorMessage("Undefined API to getPrice() on URL " . $this->config['price']['url']);
    return false;
  }
}

$tools = new Tools();
$tools->setDebug($debug);
$tools->setConfig($config);
$tools->setMemcache($memcache);
