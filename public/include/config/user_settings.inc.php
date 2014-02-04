<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Load the settings available in this system
if ($config['twofactor']['enabled'] && ($config['twofactor']['options']['details'] || $config['twofactor']['options']['changepw'] || $config['twofactor']['options']['withdraw'])) {
  $uSettings['lock'][] = array(
      'display' => 'Lock Confirmation Settings', 'type' => 'select',
      'options' => array( 0 => 'No', 1 => 'Yes' ),
      'default' => 0,
      'name' => 'lock_settings', 'value' => $uSetting->getValue('lock_settings', @$_SESSION['USERDATA']['id']),
      'tooltip' => 'Once enabled, Confirmation settings cannot be changed unless unlocked, requiring e-mail confirmation'
  );
  if ($config['twofactor']['options']['details']) {
    $uSettings['confirmation'][] = array(
        'display' => 'Edit Account Details', 'type' => 'select',
        'options' => array( 0 => 'No', 1 => 'Yes' ),
        'default' => 0,
        'name' => 'confirm_account', 'value' => $uSetting->getValue('confirm_account', @$_SESSION['USERDATA']['id']),
        'tooltip' => 'Require confirmation via e-mail to edit account information'
    );
  }
  if ($config['twofactor']['options']['withdraw']) {
    $uSettings['confirmation'][] = array(
        'display' => 'Withdraw', 'type' => 'select',
        'options' => array( 0 => 'No', 1 => 'Yes' ),
        'default' => 0,
        'name' => 'confirm_withdraw', 'value' => $uSetting->getValue('confirm_withdraw', @$_SESSION['USERDATA']['id']),
        'tooltip' => 'Require confirmation via e-mail to withdraw funds'
    );
  }
  if ($config['twofactor']['options']['changepw']) {
    $uSettings['confirmation'][] = array(
        'display' => 'Change Password', 'type' => 'select',
        'options' => array( 0 => 'No', 1 => 'Yes' ),
        'default' => 0,
        'name' => 'confirm_changepw', 'value' => $uSetting->getValue('confirm_changepw', @$_SESSION['USERDATA']['id']),
        'tooltip' => 'Require confirmation via e-mail to change password'
    );
  }
  if ($config['twofactor']['mode'] == 'gauth') {
    $uSettings['GoogleAuth'][] = array(
        'link' => '?page=account&action=gauth'
    );
  }
}

