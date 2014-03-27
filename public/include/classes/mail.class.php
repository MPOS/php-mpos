<?php
(!cfip()) ? header('HTTP/1.1 401 Unauthorized') : 0;

class Mail extends Base {
  /**
  * Mail form contact site admin
  * @param senderName string senderName
  * @param senderEmail string senderEmail
  * @param senderSubject string senderSubject
  * @param senderMessage string senderMessage
  * @param email string config Email address
  * @param subject string header subject
  * @return bool
  **/
  public function contactform($senderName, $senderEmail, $senderSubject, $senderMessage) {
    $this->debug->append("STA " . __METHOD__, 4);
    if (preg_match('/[^a-z_\.\!\?\-0-9\\s ]/i', $senderName)) {
      $this->setErrorMessage($this->getErrorMsg('E0024'));
      return false;
    }
    if (empty($senderEmail) || !filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
      $this->setErrorMessage($this->getErrorMsg('E0023'));
      return false;
    }
    if (preg_match('/[^a-z_\.\!\?\-0-9\\s ]/i', $senderSubject)) {
      $this->setErrorMessage($this->getErrorMsg('E0034'));
      return false;
    }
    if (strlen(strip_tags($senderMessage)) < strlen($senderMessage)) {
      $this->setErrorMessage($this->getErrorMsg('E0024'));
      return false;
    }
    $aData['senderName'] = $senderName;
    $aData['senderEmail'] = $senderEmail;
    $aData['senderSubject'] = $senderSubject;
    $aData['senderMessage'] = $senderMessage;
    $aData['email'] = $this->setting->getValue('website_email');
    $aData['subject'] = 'Contact Form';
      if ($this->sendMail('contactform/body', $aData)) {
        return true;
     } else {
       $this->setErrorMessage( 'Unable to send email' );
       return false;
     }
    return false;
  }

  /**
   * Send a mail with templating via Smarty
   * @param template string Template name within the mail folder, no extension
   * @param aData array Data array with some required fields
   *     SUBJECT : Mail Subject
   *     email   : Destination address
   **/
  public function sendMail($template, $aData) {
    // Prepare SMTP transport and mailer
    $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
    $mailer = Swift_Mailer::newInstance($transport);
    // Prepare the smarty templates used
    $this->smarty->clearCache(BASEPATH . 'templates/mail/' . $template . '.tpl');
    $this->smarty->clearCache(BASEPATH . 'templates/mail/subject.tpl');
    $this->smarty->assign('WEBSITENAME', $this->setting->getValue('website_name'));
    $this->smarty->assign('SUBJECT', $aData['subject']);
    $this->smarty->assign('DATA', $aData);
    // Create new message for Swiftmailer
    $message = Swift_Message::newInstance()
      ->setSubject($this->smarty->fetch(BASEPATH . 'templates/mail/subject.tpl'))
      ->setFrom(array( $this->setting->getValue('website_email') => $this->setting->getValue('website_name')))
      ->setTo($aData['email'])
      ->setSender($this->setting->getValue('website_email'))
      ->setReturnPath($this->setting->getValue('website_email'))
      ->setBody($this->smarty->fetch(BASEPATH . 'templates/mail/' . $template . '.tpl'), 'text/html');
    if (strlen(@$aData['senderName']) > 0 && @strlen($aData['senderEmail']) > 0 )
      $message->setReplyTo(array($aData['senderEmail'] => $aData['senderName']));
    if ($mailer->send($message))
      return true;
    $this->setErrorMessage($this->sqlError('E0031'));
    return false;
  }
}

// Make our class available automatically
$mail = new Mail ();
$mail->setDebug($debug);
$mail->setMysql($mysqli);
$mail->setSmarty($smarty);
$mail->setConfig($config);
$mail->setSetting($setting);
$mail->setErrorCodes($aErrorCodes);
?>
