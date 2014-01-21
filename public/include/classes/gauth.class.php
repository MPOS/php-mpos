<?php

/*
 * This file just shows a simple example of how to extend the GoogleAuthenticator schema
 */
require_once(INCLUDE_DIR."/lib/gauth/lib/ga4php.php");


/* we now define the three methods we have to define
 * the way the class can get and save data. The class calls
 * these methods frequently
 * 
 * What we need to define is methods for saving the auth data
 * for the user
 * 
 * Lets assume our application already has a user table, so what 
 * we do is add a new column into our table for saving auth data
 * 
 * i.e. "alter table users add tokendata text" would add a column
 * we can use to our "users" tables... Lets also assume that we
 * have a column called "username" which defines the username
 * 
 * Lastly, lets assume we can get a connection to the database
 * by calling a function GetDatabase(); which is a PDO object
 */
class GAuth extends GoogleAuthenticator {
  function getData($username) {
    $stmt = $this->mysqli->prepare("select * from accounts where username = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $username) && $stmt->execute() && $result = $stmt->get_result()) {
      if(!$result) return false;
      $tokendata = false;
      foreach ($result as $row) {
        $tokendata = $row["token"];
      }
      return $tokendata;
    }
  }
  function putData($username, $data) {
    $stmt = $this->mysqli->prepare("update accounts set gauth_key = ? where username = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ss', $data, $username) && $stmt->execute() && $result = $stmt->get_result()) {
      if ($result->num_rows == 1) {
        // update success, yay
        return true;
      } else {
        // update failed, oops?
        return false;
      }
    } else {
        // update failed, oops?
      return false;
    }
  }
  function getUsers() { 
    // lol we have to implement this... says the class, try to make me
  }
}
class GAuthUser extends Base {
  private $GAuth;
  public function __construct() {
    $this->GAuth = new Gauth();
  }
  public function authUser($username, $token) {
    $this->GAuth->authenticateUser($username, $token);
  }
}
$GAuth = new GAuthUser();
$GAuth->setDebug($debug);
$GAuth->setMysql($mysqli);
$GAuth->setConfig($config);
$GAuth->setErrorCodes($aErrorCodes);
?>
