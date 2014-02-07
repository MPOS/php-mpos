<?php 
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

/**
 * Misc
 *  Extra security settings
 *
 **/
$config['https_only'] = false;
$config['mysql_filter'] = true;
$config['protect_session_state'] = false;

/**
 * Logging
 *  Emergency = 0, Alert     = 1,  Critical  = 2
 *  Error     = 3, Warn      = 4,  Notice    = 5
 *  Info      = 6, Debug     = 7,  Nothing   = 8
 */
$config['logging']['enabled'] = true;
$config['logging']['level'] = 6;
$config['logging']['path'] = realpath(BASEPATH.'../logs');

/**
 * Memcache Rate Limiting
 *  Rate limit requests using Memcache
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-memcache-rate-limiting
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
 * CSRF Protection
 *  Enable or disable CSRF protection
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-csrf-protection
 */
$config['csrf']['enabled'] = true;

/**
 * E-mail confirmations for user actions
 *  Two-factor confirmation for user actions
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-e-mail-confirmations
 */
$config['twofactor']['enabled'] = true;
$config['twofactor']['options']['details'] = true;
$config['twofactor']['options']['withdraw'] = true;
$config['twofactor']['options']['changepw'] = true;

/**
 * Lock account after X
 *  Lock accounts after X invalid logins or pins
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-lock-accounts-after-failed-logins
 **/
$config['maxfailed']['login'] = 3;
$config['maxfailed']['pin'] = 3;

?>
