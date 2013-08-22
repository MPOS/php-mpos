<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

/**
 * Helper class for our API
 **/
class Api extends Base {
  function isActive($error=true) {
    if (!$this->setting->getValue('disable_api')) {
      return true;
    } else {
      if ($error == true) {
        header('HTTP/1.1 501 Not implemented');
        die('501 Not implemented');
      }
    }
  }
}

$api = new Api();
$api->setConfig($config);
$api->setSetting($setting);
