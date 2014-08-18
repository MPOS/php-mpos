<?php

// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');

// Send email
$aMailData = array(
    'email' => $setting->getValue('system_error_email'),
    'subject' => 'Test email from mining pool',
    'coinname' => $config['gettingstarted']['coinname'],
    'stratumurl' => $config['gettingstarted']['stratumurl'],
    'stratumport' => $config['gettingstarted']['stratumport']
);

if (!$mail->sendMail('notifications/test_email', $aMailData))
    echo "Failed to send test email" . PHP_EOL;
