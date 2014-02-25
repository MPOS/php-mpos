<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class Referral extends Base {
  protected $table = 'referrals';







}

$referral = new Referral();
$referral->setDebug($debug);
$referral->setMysql($mysqli);
$referral->setMemcache($memcache);
$referral->setShare($share);
$referral->setConfig($config);
$referral->setUser($user);
$referral->setErrorCodes($aErrorCodes);

?>