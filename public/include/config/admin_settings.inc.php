<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

$aThemes = $template->getThemes();

// Load the settings available in this system
$aSettings['website'][] = array(
  'display' => 'Maintenance Mode', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'maintenance', 'value' => $setting->getValue('maintenance'),
  'tooltip' => 'Enable or Disable maintenance mode. Only admins can still login.'
);
$aSettings['website'][] = array(
  'display' => 'Message of the Day', 'type' => 'text',
  'size' => 25,
  'default' => '',
  'name' => 'system_motd', 'value' => $setting->getValue('system_motd'),
  'tooltip' => 'Display a message of the day as information popup if set.'
);
$aSettings['website'][] = array(
  'display' => 'Website Name', 'type' => 'text',
  'size' => 25,
  'default' => 'The Pool',
  'name' => 'website_name', 'value' => $setting->getValue('website_name'),
  'tooltip' => 'The name of you pool page, displayed in the header of the page.'
);
$aSettings['website'][] = array(
  'display' => 'Website Title', 'type' => 'text',
  'size' => 25,
  'default' => 'The Pool - Mining Evolved',
  'name' => 'website_title', 'value' => $setting->getValue('website_title'),
  'tooltip' => 'The title of you pool page, displayed in the browser window header.'
);
$aSettings['website'][] = array(
  'display' => 'Website Slogan', 'type' => 'text',
  'size' => 25,
  'default' => 'Resistance is Futile',
  'name' => 'website_slogan', 'value' => $setting->getValue('website_slogan'),
  'tooltip' => 'The slogan of you pool page, displayed in the browser window header.'
);
$aSettings['website'][] = array(
  'display' => 'Website e-mail', 'type' => 'text',
  'size' => 25,
  'default' => 'test@example.com',
  'name' => 'website_email', 'value' => $setting->getValue('website_email'),
  'tooltip' => 'The email address for your pool, used in mail templates and notifications.'
);
$aSettings['website'][] = array(
  'display' => 'Website theme', 'type' => 'select',
  'options' => $aThemes,
  'default' => 'mpos',
  'name' => 'website_theme', 'value' => $setting->getValue('website_theme'),
  'tooltip' => 'The default theme used on your pool.'
);
$aSettings['website'][] = array(
  'display' => 'Website mobile theme', 'type' => 'select',
  'options' => $aThemes,
  'default' => 'mobile',
  'name' => 'website_mobile_theme', 'value' => $setting->getValue('website_mobile_theme'),
  'tooltip' => 'The mobile theme used for your pool.'
);
$aSettings['blockchain'][] = array(
  'display' => 'Disable Blockexplorer', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'website_blockexplorer_disabled', 'value' => $setting->getValue('website_blockexplorer_disabled'),
  'tooltip' => 'Enabled or disable the blockexplorer URL feature. Will remove any links using the blockexplorer URL.'
);
$aSettings['blockchain'][] = array(
  'display' => 'Blockexplorer URL', 'type' => 'text',
  'size' => 50,
  'default' => 'http://explorer.litecoin.net/block/',
  'name' => 'website_blockexplorer_url', 'value' => $setting->getValue('website_blockexplorer_url'),
  'tooltip' => 'URL to the blockexplorer website for your blockchain. Will append the blockhash to the URL. Leave empty to disabled this.'
);
$aSettings['blockchain'][] = array(
  'display' => 'Disable Transactionexplorer', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'website_transactionexplorer_disabled', 'value' => $setting->getValue('website_transactionexplorer_disabled'),
  'tooltip' => 'Enabled or disable the transactionexplorer URL feature. Will remove any links using the transactionexplorer URL.'
);
$aSettings['blockchain'][] = array(
  'display' => 'Transactionexplorer URL', 'type' => 'text',
  'size' => 50,
  'default' => 'http://explorer.litecoin.net/tx/',
  'name' => 'website_transactionexplorer_url', 'value' => $setting->getValue('website_transactionexplorer_url'),
  'tooltip' => 'URL to the transactionexplorer website for your blockchain. Will append the transactionid to the URL. Leave empty to disabled this.'
);
$aSettings['blockchain'][] = array(
  'display' => 'Disable Chaininfo', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'website_chaininfo_disabled', 'value' => $setting->getValue('website_chaininfo_disabled'),
  'tooltip' => 'Enabled or disable the chainfo URL feature. Will remove any links using the chaininfo URL.'
);
$aSettings['blockchain'][] = array(
  'display' => 'Chaininfo URL', 'type' => 'text',
  'size' => 50,
  'default' => 'http://allchains.info',
  'name' => 'website_chaininfo_url', 'value' => $setting->getValue('website_chaininfo_url'),
  'tooltip' => 'URL to the chaininfo website for your blockchain. Leave empty to disabled this.'
);
$aSettings['wallet'][] = array(
  'display' => 'Cold Coins', 'type' => 'text',
  'size' => 6,
  'default' => 0,
  'name' => 'wallet_cold_coins', 'value' => $setting->getValue('wallet_cold_coins'),
  'tooltip' => 'Amount of coins held in a pools cold wallet.'
);
$aSettings['statistics'][] = array(
  'display' => 'Ajax Refresh Interval', 'type' => 'select',
  'options' => array('5' => '5', '10' => '10', '15' => '15', '30' => '30', '60' => '60' ),
  'default' => 10,
  'name' => 'statistics_ajax_refresh_interval', 'value' => $setting->getValue('statistics_ajax_refresh_interval'),
  'tooltip' => 'How often to refresh data via ajax in seconds.'
);
$aSettings['statistics'][] = array(
  'display' => 'Ajax Long Refresh Interval', 'type' => 'select',
  'options' => array('5' => '5', '10' => '10', '15' => '15', '30' => '30', '60' => '60', '600' => '600', '1800' => '1800', '3600' => '3600' ),
  'default' => 10,
  'name' => 'statistics_ajax_long_refresh_interval', 'value' => $setting->getValue('statistics_ajax_long_refresh_interval'),
  'tooltip' => 'How often to refresh data via ajax in seconds. User for SQL costly queries like getBalance and getWorkers.'
);
$aSettings['statistics'][] = array(
  'display' => 'Ajax Data Interval', 'type' => 'select',
  'options' => array('60' => '1', '180' => '3', '300' => '5', '600' => '10'),
  'default' => 300,
  'name' => 'statistics_ajax_data_interval', 'value' => $setting->getValue('statistics_ajax_data_interval'),
  'tooltip' => 'Time in minutes, interval for hashrate and sharerate calculations. Higher intervals allow for better accuracy at a higer server load.'
);
$aSettings['statistics'][] = array(
  'display' => 'Block Statistics Count', 'type' => 'text',
  'size' => 25,
  'default' => 20,
  'name' => 'statistics_block_count', 'value' => $setting->getValue('statistics_block_count'),
  'tooltip' => 'Blocks to fetch for the block statistics page.'
);
$aSettings['statistics'][] = array(
  'display' => 'Show block average', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'statistics_show_block_average', 'value' => $setting->getValue('statistics_show_block_average'),
  'tooltip' => 'Show block average in block statistics page.'
);
$aSettings['statistics'][] = array(
  'display' => 'Pool Hashrate Modifier', 'type' => 'select',
  'options' => array( '1' => 'KH/s', '0.001' => 'MH/s', '0.000001' => 'GH/s' ),
  'default' => '1',
  'name' => 'statistics_pool_hashrate_modifier', 'value' => $setting->getValue('statistics_pool_hashrate_modifier'),
  'tooltip' => 'Auto-adjust displayed pool hashrates to a certain limit.'
);
$aSettings['statistics'][] = array(
  'display' => 'Network Hashrate Modifier', 'type' => 'select',
  'options' => array( '1' => 'KH/s', '0.001' => 'MH/s', '0.000001' => 'GH/s' ),
  'default' => '1',
  'name' => 'statistics_network_hashrate_modifier', 'value' => $setting->getValue('statistics_network_hashrate_modifier'),
  'tooltip' => 'Auto-adjust displayed network hashrates to a certain limit.'
);
$aSettings['statistics'][] = array(
  'display' => 'Personal Hashrate Modifier', 'type' => 'select',
  'options' => array( '1' => 'KH/s', '0.001' => 'MH/s', '0.000001' => 'GH/s' ),
  'default' => '1',
  'name' => 'statistics_personal_hashrate_modifier', 'value' => $setting->getValue('statistics_personal_hashrate_modifier'),
  'tooltip' => 'Auto-adjust displayed personal hashrates to a certain limit.'
);
$aSettings['statistics'][] = array(
  'display' => 'Enable Google analytics', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'statistics_analytics_enabled', 'value' => $setting->getValue('statistics_analytics_enabled'),
  'tooltip' => 'Enable or Disable Google Analytics.'
);
$aSettings['statistics'][] = array(
  'display' => 'Google Analytics Code', 'type' => 'textarea',
  'size' => 20,
  'height' => 12,
  'default' => 'Code from Google Analytics',
  'name' => 'statistics_analytics_code', 'value' => $setting->getValue('statistics_analytics_code'),
  'tooltip' => '.'
);
$aSettings['acl'][] = array(
  'display' => 'Hide news post author', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'acl_hide_news_author', 'value' => $setting->getValue('acl_hide_news_author'),
  'tooltip' => 'Should the news author username be hidden.'
);
$aSettings['acl'][] = array(
  'display' => 'Pool Statistics', 'type' => 'select',
  'options' => array( 0 => 'Private', 1 => 'Public'),
  'default' => 1,
  'name' => 'acl_pool_statistics', 'value' => $setting->getValue('acl_pool_statistics'),
  'tooltip' => 'Make the pool statistics page private (users only) or public.'
);
$aSettings['acl'][] = array(
  'display' => 'Block Statistics', 'type' => 'select',
  'options' => array( 0 => 'Private', 1 => 'Public'),
  'default' => 1,
  'name' => 'acl_block_statistics', 'value' => $setting->getValue('acl_block_statistics'),
  'tooltip' => 'Make the block statistics page private (users only) or public.'
);
$aSettings['acl'][] = array(
  'display' => 'Round Statistics', 'type' => 'select',
  'options' => array( 0 => 'Private', 1 => 'Public'),
  'default' => 1,
  'name' => 'acl_round_statistics', 'value' => $setting->getValue('acl_round_statistics'),
  'tooltip' => 'Make the round statistics page private (users only) or public.'
);
$aSettings['acl'][] = array(
  'display' => 'Block Finder Statistics', 'type' => 'select',
  'options' => array( 0 => 'Private', 1 => 'Public'),
  'default' => 1,
  'name' => 'acl_blockfinder_statistics', 'value' => $setting->getValue('acl_blockfinder_statistics'),
  'tooltip' => 'Make the Block Finder Statistics page private (users only) or public.'
);
$aSettings['acl'][] = array(
  'display' => 'Uptime Statistics', 'type' => 'select',
  'options' => array( 0 => 'Private', 1 => 'Public'),
  'default' => 1,
  'name' => 'acl_uptime_statistics', 'value' => $setting->getValue('acl_uptime_statistics'),
  'tooltip' => 'Make the uptime statistics page private (users only) or public.'
);
$aSettings['system'][] = array(
  'display' => 'E-mail address for system error notifications', 'type' => 'text',
  'size' => 25,
  'default' => 'test@example.com',
  'name' => 'system_error_email', 'value' => $setting->getValue('system_error_email'),
  'tooltip' => 'The email address for system errors notifications, like cronjobs failures.'
);
$aSettings['system'][] = array(
  'display' => 'Disable e-mail confirmations', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'accounts_confirm_email_disabled', 'value' => $setting->getValue('accounts_confirm_email_disabled'),
  'tooltip' => 'Should users supply a valid e-mail address upon registration. Requires them to confirm the address before accounts are activated.'
);
$aSettings['system'][] = array(
  'display' => 'Disable registrations', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'lock_registration', 'value' => $setting->getValue('lock_registration'),
  'tooltip' => 'Enable or Disable registrations. Useful to create an invitation only pool.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Invitations', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'disable_invitations', 'value' => $setting->getValue('disable_invitations'),
  'tooltip' => 'Enable or Disable invitations. Users will not be able to invite new users via email if disabled.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Payout Cron', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'disable_payouts', 'value' => $setting->getValue('disable_payouts'),
  'tooltip' => 'Enable or Disable the payout cronjob. Users will not be able to withdraw any funds if disabled. Will be logged in monitoring page.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Manual Payouts', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'disable_manual_payouts', 'value' => $setting->getValue('disable_manual_payouts'),
  'tooltip' => 'Enable or Disable manual payouts. Users will not be able to withdraw any funds manually if disabled. Will NOT be logged in monitoring page.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Auto Payout', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'disable_auto_payouts', 'value' => $setting->getValue('disable_auto_payouts'),
  'tooltip' => 'Enable or Disable the payout cronjob. Users will not be able to withdraw any funds automatically if disabled. Will NOT be logged in monitoring page.'
);
$aSettings['system'][] = array(
  'display' => 'Disable notifications', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'disable_notifications', 'value' => $setting->getValue('disable_notifications'),
  'tooltip' => 'Enable or Disable system notifications. This includes new found blocks, monitoring and all other notifications.'
);
$aSettings['system'][] = array(
  'display' => 'Disable API', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'disable_api', 'value' => $setting->getValue('disable_api'),
  'tooltip' => 'Enable or Disable the pool wide API functions. See API reference on Github for details.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Contactform', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'disable_contactform', 'value' => $setting->getValue('disable_contactform'),
  'tooltip' => 'Enable or Disable Contactform. Users will not be able to use the contact form.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Contactform for Guests', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'disable_contactform_guest', 'value' => $setting->getValue('disable_contactform_guest'),
  'tooltip' => 'Enable or Disable Contactform for guests. Guests will not be able to use the contact form.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Donors Page', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes'),
  'default' => 1,
  'name' => 'disable_donors', 'value' => $setting->getValue('disable_donors'),
  'tooltip' => 'Showing Donors page in Navigation.'
);
$aSettings['system'][] = array(
  'display' => 'Disable About Page', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes'),
  'default' => 1,
  'name' => 'disable_about', 'value' => $setting->getValue('disable_about'),
  'tooltip' => 'Showing About page in Navigation.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Live Dashboard', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes'),
  'default' => 0,
  'name' => 'disable_dashboard', 'value' => $setting->getValue('disable_dashboard'),
  'tooltip' => 'Disable live updates on the dashboard to reduce server load.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Dashboard API', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes'),
  'default' => 0,
  'name' => 'disable_dashboard_api', 'value' => $setting->getValue('disable_dashboard_api'),
  'tooltip' => 'Disable dashboard API entirely to reduce server load.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Live Navbar', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes'),
  'default' => 0,
  'name' => 'disable_navbar', 'value' => $setting->getValue('disable_navbar'),
  'tooltip' => 'Disable live updates on the navbar to reduce server load.'
);
$aSettings['system'][] = array(
  'display' => 'Disable Navbar API', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes'),
  'default' => 0,
  'name' => 'disable_navbar_api', 'value' => $setting->getValue('disable_navbar_api'),
  'tooltip' => 'Disable navbar API entirely to reduce server load. Used in pool stats and navbar mini stats.'
);
$aSettings['system'][] = array(
  'display' => 'Disable TX Summaries', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes'),
  'default' => 0,
  'name' => 'disable_transactionsummary', 'value' => $setting->getValue('disable_transactionsummary'),
  'tooltip' => 'Disable transaction summaries. Helpful with large transaction tables.'
);
$aSettings['recaptcha'][] = array(
  'display' => 'Enable re-Captcha', 'type' => 'select',
  'options' => array( 0 => 'No', 1 => 'Yes' ),
  'default' => 0,
  'name' => 'recaptcha_enabled', 'value' => $setting->getValue('recaptcha_enabled'),
  'tooltip' => 'Enable or Disable re-Captcha. This will require user input on registraion and other forms.'
);
$aSettings['recaptcha'][] = array(
  'display' => 're-Captcha Private Key', 'type' => 'text',
  'size' => 25,
  'default' => 'YOUR_PRIVATE_KEY',
  'name' => 'recaptcha_private_key', 'value' => $setting->getValue('recaptcha_private_key'),
  'tooltip' => '.'
);
$aSettings['recaptcha'][] = array(
  'display' => 're-Captcha Public Key', 'type' => 'text',
  'size' => 25,
  'default' => 'YOUR_PUBLIC_KEY',
  'name' => 'recaptcha_public_key', 'value' => $setting->getValue('recaptcha_public_key'),
  'tooltip' => 'Your public key as given by your re-Captcha account.'
);
$aSettings['monitoring'][] = array(
  'display' => 'Uptime Robot Private API Key', 'type' => 'text',
  'size' => 100,
  'default' => '<API KEY>|<MONITOR ID>,<API KEY>|<MONITOR ID>, ...',
  'name' => 'monitoring_uptimerobot_api_keys', 'value' => $setting->getValue('monitoring_uptimerobot_api_keys'),
  'tooltip' => 'Create per-monitor API keys and save them here to propagate your uptime statistics.'
);

