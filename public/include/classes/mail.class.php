<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Mail {
  private $sError = '';

  public function setDebug($debug) {
    $this->debug = $debug;
  }
  public function setMysql($mysqli) {
    $this->mysqli = $mysqli;
  }
  public function setSmarty($smarty) {
    $this->smarty = $smarty;
  }
  public function setUser($user) {
    $this->user = $user;
  }
  public function setConfig($config) {
    $this->config = $config;
  }
  public function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }
  function checkStmt($bState) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Internal application Error');
      return false;
    }
    return true;
  }

  public function sendMail($template, $aData) {
    $this->smarty->assign('WEBSITENAME', $this->config['website']['name']);
    $this->smarty->assign('SUBJECT', $aData['subject']);
    $this->smarty->assign('DATA', $aData);
    $headers = 'From: Website Administration <' . $this->config['website']['email'] . ">\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    if (mail($aData['email'],
      $this->smarty->fetch(BASEPATH . 'templates/mail/subject.tpl'),
      $this->smarty->fetch(BASEPATH . 'templates/mail/' . $template  . '.tpl'),
      $headers)) {
        return true;
      } else {
        $this->setErrorMessage("Unable to send mail");
        return false;
      }
    return false;
  }
}

// Make our class available automatically
$mail = new Mail ();
$mail->setDebug($debug);
$mail->setMysql($mysqli);
$mail->setSmarty($smarty);
$mail->setConfig($config);
?>
