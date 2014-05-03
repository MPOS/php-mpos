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
    $aData['email'] = $this->setting->getValue('website_email', 'test@example.com');
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
   * Send a mail with templating via Smarty and Siftmailer
   * @param template string Template name within the mail folder, no extension
   * @param aData array Data array with some required fields
   *     subject : Mail Subject
   *     email   : Destination address
   **/
  public function sendMail($template, $aData, $throttle=false) {
    // Prepare SMTP transport and mailer
    $transport_type = $this->config['swiftmailer']['type'];
    if ($transport_type == 'sendmail') {
      $transport = Swift_SendmailTransport::newInstance($this->config['swiftmailer'][$transport_type]['path'] . ' ' . $this->config['swiftmailer'][$transport_type]['options']);
    } else if ($this->config['swiftmailer']['type'] == 'smtp') {
      $transport = Swift_SmtpTransport::newInstance($this->config['swiftmailer']['smtp']['host'], $this->config['swiftmailer']['smtp']['port'], $this->config['swiftmailer']['smtp']['encryption']);
      if (!empty($this->config['swiftmailer']['smtp']['username']) && !empty($this->config['swiftmailer']['smtp']['password'])) {
        $transport->setUsername($this->config['swiftmailer']['smtp']['username']);
        $transport->setPassword($this->config['swiftmailer']['smtp']['password']);
      }
    }
    $mailer = Swift_Mailer::newInstance($transport);

    // Throttle mails to x per minute, used for newsletter for example
    if ($this->config['swiftmailer']['type'] == 'smtp' && $throttle) {
      $mailer->registerPlugin(new Swift_Plugins_ThrottlerPlugin(
        $this->config['swiftmailer']['smtp']['throttle'], Swift_Plugins_ThrottlerPlugin::MESSAGES_PER_MINUTE
      ));
    }

    // Prepare the smarty templates used
    $this->smarty->clearCache(TEMPLATE_DIR . '/mail/' . $template . '.tpl');
    $this->smarty->clearCache(TEMPLATE_DIR . '/mail/subject.tpl');
    $this->smarty->assign('WEBSITENAME', $this->setting->getValue('website_name'));
    $this->smarty->assign('SUBJECT', $aData['subject']);
    $this->smarty->assign('DATA', $aData);

    // Create new message for Swiftmailer
    $senderEmail = $this->setting->getValue('website_email', 'test@example.com');
    $senderName = $this->setting->getValue('website_name', 'test@example.com');
    $message = Swift_Message::newInstance()
      ->setSubject($this->smarty->fetch(TEMPLATE_DIR . '/mail/subject.tpl'))
      ->setFrom(array( $senderEmail => $senderName))
      ->setTo($aData['email'])
      ->setSender($senderEmail)
      ->setReturnPath($senderEmail)
      ->setBody($this->smarty->fetch(TEMPLATE_DIR . '/mail/' . $template . '.tpl'), 'text/html');
    if (isset($aData['senderName']) &&
        isset($aData['senderEmail']) &&
        strlen($aData['senderName']) > 0 &&
        strlen($aData['senderEmail']) > 0 &&
        filter_var($aData['senderEmail'], FILTER_VALIDATE_EMAIL))
      $message->setReplyTo(array($aData['senderEmail'] => $aData['senderName']));

    // Send message out with configured transport
    try {
      if ($mailer->send($message)) return true;
    } catch (Exception $e) {
      $this->setErrorMessage($e->getMessage());
      return false;
    }
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
