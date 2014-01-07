<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

/**
 * Helper class for our API
 **/
class Api extends Base {
  private $api_version = '1.0.0';

  function setStartTime($dStartTime) {
    $this->dStartTime = $dStartTime;
  }
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

  /**
   * Create API json object from input array
   * @param data Array data to create JSON for
   * @param force bool Enforce a JSON object
   * @return string JSON object
   **/
  function get_json($data, $force=false) {
    return json_encode(
      array( $_REQUEST['action'] => array(
        'version' => $this->api_version,
        'runtime' => (microtime(true) - $this->dStartTime) * 1000,
        'data' => $data
      )), $force ? JSON_FORCE_OBJECT : 0
    );
  }

  /**
   * Check user access level to the API call
   **/
  function checkAccess($user_id, $get_id=NULL) {
    if ( ! $this->user->isAdmin($user_id) && (!empty($get_id) && $get_id != $user_id)) {
      // User is NOT admin and tries to access an ID that is not their own
      header("HTTP/1.1 401 Unauthorized");
      die("Access denied");
    } else if ($this->user->isAdmin($user_id) && !empty($get_id)) {
      // User is an admin and tries to fetch another users data
      $id = $get_id;
      // Is it a username or a user ID
      ctype_digit($_REQUEST['id']) ? $id = $get_id : $id = $this->user->getUserId($get_id);
    } else {
      $id = $user_id;
    }
    return $id;
  }
}

$api = new Api();
$api->setConfig($config);
$api->setUser($user);
$api->setSetting($setting);
$api->setStartTime($dStartTime=microtime(true));
