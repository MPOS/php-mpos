<?php 
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

/**
 * Strict is a set of extra security options can use that when enabled can help protect against
 * a few different types of attacks.
 *
 * You must have Memcache enabled and configured & Memcache anti-dos configured to use this!
 *
 *   Check -> Memcache configuration
 *   Check -> Memcache anti resource-dos
 *
 *     Options                          Default      Explanation
 *     -------                      +   -------   +  -----------
 *     strict                       :    true     :  Whether or not to use strict mode
 *     __https_only		            :    false    :  Requires/pushes to https
 *     __mysql_filter               :    true     :  Uses a mysqli shim to use php filters on all incoming data
 *     __verify_client              :    true     :  Verifies the client using specified settings
 *     __verify_client_ip           :    true     :  If the client request suddenly switches IP, trigger a failure
 *     __verify_client_useragent    :    true     :  If the client request suddenly switches Useragent, trigger a failure
 *     __verify_client_sessionid    :    true     :  If the client request suddenly switches SessionID, trigger a failure
 *     __verify_client_fails        :    0        :  Maximum number of client-side inconsistencies to accept before revoking sessions
 *     __verify_server              :    false    :  Verifies the server is valid for this request
 *     __bind_protocol              :    https    :  Server validate protocol; http or https
 *     __bind_host                  :    ''       :  Server validate host; ie. your domain or subdomain
 *     __bind_port                  :    443      :  Server validate port; 80 / 443 / something else
 **/
$config['strict'] = true;
$config['strict__https_only'] = false;
$config['strict__mysql_filter'] = true;
$config['strict__verify_client'] = true;
$config['strict__verify_client_ip'] = true;
$config['strict__verify_client_useragent'] = true;
$config['strict__verify_client_sessionid'] = true;
$config['strict__verify_client_fails'] = 0;
$config['strict__verify_server'] = false;
$config['strict__bind_protocol'] = 'https';
$config['strict__bind_host'] = '';
$config['strict__bind_port'] = 443;

/**
 * Memcache anti resource-dos protection / request rate limiting
 *
 * Explanation:
 *   Because bots/angry users can just fire away at pages or f5 us to death, we can attempt to rate limit requests
 *   using memcache - now shares data with session manager.
 *
 * Options:
 *   enabled              =   Whether or not we will try to rate limit requests
 *   protect_ajax         =   If enabled, we will also watch the ajax calls for rate limiting and kill bad requests
 *   ajax_hits_additive   =   If enabled, ajax hits will count towards the site counter as well as the ajax counter
 *   flush_seconds_api    =   Number of seconds between each flush of user/ajax counter
 *   rate_limit_api       =   Number of api requests allowed per flush_seconds_api
 *   flush_seconds_site   =   Number of seconds between each flush of user/site counter
 *   rate_limit_site      =   Number of site requests allowed per flush_seconds_site
 *   ignore_admins        =   Ignores the rate limit for admins
 *   error_push_page      =   Page/action array to push users to a specific page, look in the URL!
 *                             Empty = 'You are sending too many requests too fast!' on a blank page
 * Default:
 *   enabled              =   true
 *   protect_ajax         =   true
 *   ajax_hits_additive   =   false
 *   flush_seconds_api    =   60
 *   rate_limit_api       =   20
 *   flush_seconds_site   =   60
 *   rate_limit_site      =   30
 *   ignore_admins        =   true
 *   error_push_page      =   array('page' => 'error', 'action' => 'ratelimit');
 */
$config['mc_antidos']['enabled'] = true;
$config['mc_antidos']['protect_ajax'] = true;
$config['mc_antidos']['ajax_hits_additive'] = false;
$config['mc_antidos']['flush_seconds_api'] = 60;
$config['mc_antidos']['rate_limit_api'] = 20;
$config['mc_antidos']['flush_seconds_site'] = 60;
$config['mc_antidos']['rate_limit_site'] = 30;
$config['mc_antidos']['ignore_admins'] = true;
$config['mc_antidos']['error_push_page'] = array('page' => 'error', 'action' => 'ratelimit');

/**
 * CSRF protection config
 *
 * Explanation:
 *   To help protect against CSRF, we can generate a hash that changes every minute
 *   and is unique for each user/IP and page or use, and check against that when a
 *   form is submitted.
 *
 * Options:
 *   enabled          =   Whether or not we will generate/check for valid CSRF tokens
 * Default:
 *   enabled          =   true
 */
$config['csrf']['enabled'] = true;

/**
 * E-mail confirmations for user actions
 *
 * Explanation:
 *   To increase security for users, account detail changes can require
 *   an e-mail confirmation prior to performing certain actions.
 *
 * Options:
 *   enabled   :  Whether or not to require e-mail confirmations
 *   details   :  Require confirmation to change account details
 *   withdraw  :  Require confirmation to manually withdraw/payout
 *   changepw  :  Require confirmation to change password
 *
 * Default:
 *   enabled   =  true
 *   details   =  true
 *   withdraw  =  true
 *   changepw  =  true
 */
$config['twofactor']['enabled'] = true;
$config['twofactor']['options']['details'] = true;
$config['twofactor']['options']['withdraw'] = true;
$config['twofactor']['options']['changepw'] = true;

/**
 * Lock account after maximum failed logins
 *
 * Explanation:
 *   To avoid accounts being hacked by brute force attacks,
 *   set a maximum amount of failed login or pin entry attempts before locking
 *   the account. They will need to contact site support to re-enable the account.
 *
 *   This also applies for invalid PIN entries, which is covered by the pin option.
 *
 *   Workers are not affected by this lockout, mining will continue as usual.
 *
 * Default:
 *   login  =  3
 *   pin    =  3
 **/
$config['maxfailed']['login'] = 3;
$config['maxfailed']['pin'] = 3;

?>