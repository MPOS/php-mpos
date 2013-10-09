<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Token_Type Extends Base {
  var $table = 'token_types';
  /**
   * Return ID for specific token
   * @param strName string Token Name
   * @return mixed ID on success, false on failure
   **/
  public function getTypeId($strName) {
    return $this->getSingle($strName, 'id', 'name', 's');
  }
}

$tokentype = new Token_Type();
$tokentype->setDebug($debug);
$tokentype->setMysql($mysqli);
