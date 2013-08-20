<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Load the settings available in this system
$aSettings['system'][] = array(
  'display' => 'Maintenance Mode', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'name' => 'maintenance', 'value' => $setting->getValue('maintenance'),
  'tooltip' => 'Enable or Disable maintenance mode. Only admins can still login.'
);
$aSettings['system'][] = array(
  'display' => 'Disable registrations', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'name' => 'lock_registration', 'value' => $setting->getValue('lock_registration'),
  'tooltip' => 'Enable or Disable registrations. Useful to create an invitation only pool.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Invitations', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'name' => 'disable_invitations', 'value' => $setting->getValue('disable_invitations'),
  'tooltip' => 'Enable or Disable invitations. Users will not be able to invite new users via email if disabled.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Manual Payouts', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'name' => 'disable_mp', 'value' => $setting->getValue('disable_mp'),
  'tooltip' => 'Enable or Disable the manual payout processing. Users will not be able to withdraw any funds if disabled.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Automatic Payouts', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'name' => 'disable_ap', 'value' => $setting->getValue('disable_ap'),
  'tooltip' => 'Enable or Disable the automatic payout processing. Users exceeding their thresholds will not be paid out if disabled.'
);
$aSettings['system'][] = array(
  'display' => 'Disable notifications', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'name' => 'disable_notifications', 'value' => $setting->getValue('disable_notifications'),
  'tooltip' => 'Enable or Disable system notifications. This includes new found blocks, monitoring and all other notifications.'
);
$aSettings['other'][] = array(
  'display' => 'Message of the Day', 'type' => 'text',
  'size' => 25,
  'name' => 'system_motd', 'value' => $setting->getValue('system_motd'),
  'tooltip' => 'Display a message of the day as information popup if set.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Mailform', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'name' => 'disable_mailform', 'value' => $setting->getValue('disable_mailform'),
  'tooltip' => 'Enable or Disable Mailform. Users will not be able to use the contect form.'
);

?>
