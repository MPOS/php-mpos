<?php
// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Our include directory for additional features
define('INCLUDE_DIR', BASEPATH . 'include');

// Our class directory
define('CLASS_DIR', INCLUDE_DIR . '/classes');

// Our pages directory which takes care of
define('PAGES_DIR', INCLUDE_DIR . '/pages');

// Set debugging level for our debug class
define('DEBUG', 0);

// SALT used to hash passwords
define('SALT', 'PLEASEMAKEMESOMETHINGRANDOM');

/**
 * Database configuration
 *
 * A MySQL database backend is required for mmcfe-ng.
 * Also ensure the database structure is imported!
 * The SQL file should be included in this project under the `sql` directory
 *
 * Default:
 *   host     =  'localhost'
 *   port     =  3306
 *   user     =  'someuser'
 *   pass     =  'somepass'
 *   name     =  'mmcfe_ng'
 **/
$config['db']['host'] = 'localhost';
$config['db']['user'] = 'someuser';
$config['db']['pass'] = 'somepass';
$config['db']['port'] = 3306;
$config['db']['name'] = 'mmcfe_ng';

/**
 * Local wallet RPC configuration
 *
 * mmcfe-ng uses the RPC backend to fetch transactions, blocks
 * and various other things. They need to match your coind RPC
 * configuration.
 *
 * Default:
 *   type      =  'http'
 *   host      =  'localhost:19334'
 *   username  =  'testnet'
 *   password  =  'testnet'
 **/
$config['wallet']['type'] = 'http';
$config['wallet']['host'] = 'localhost:19334';
$config['wallet']['username'] = 'testnet';
$config['wallet']['password'] = 'testnet';

/**
 * API configuration to fetch prices for set currency
 *
 * Explanation:
 *   mmcfe-ng will try to fetch the current exchange rates
 *   from this API URL/target. Currently btc-e and coinchoose
 *   are supported in mmcfe-ng. If you want to remove the trade
 *   header just set currency to an empty string.
 *
 * Default (btc-e.com):
 *   url       =  `https://btc-e.com`
 *   target    =  `/api/2/ltc_usd/ticker`
 *   currency  =  `USD`
 *
 * Optional (coinchoose.com):
 *   url       =  `http://www.coinchoose.com`
 *   target    =  `/api.php`
 *   currency  =  `BTC`
 **/
$config['price']['url'] = 'https://btc-e.com';
$config['price']['target'] = '/api/2/ltc_usd/ticker';
$config['price']['currency'] = 'USD';


/**
 * Automatic payout thresholds
 *
 * These values define the min and max settings
 * that can be entered by a user.
 * Defaults:
 *   `min` = `1`
 *   `max` = `250`
 **/
$config['ap_threshold']['min'] = 1;
$config['ap_threshold']['max'] = 250;


/**
 * Website specific configuration settings
 *
 * Explanation:
 *   title         :  Website title used in master template
 *   name          :  The pool name, displayed in the header and mails
 *   slogan        :  A special slogan, also displayed in the header below name
 *   email         :  `From` addresses used in notifications
 *   theme         :  Theme used for desktop browsers
 *   mobile        :  Enable/Disable mobile theme support
 *   mobile_theme  :  Theme used for mobile browsers
 *
 * Defaults:
 *   title         =  `The Pool - Mining Evolved`
 *   name          =  `The Pool`
 *   slogan        =  `Resistance is futile`
 *   email         =  `test@example.com`
 *   theme         =  `mmcFE`
 *   mobile        =  true
 *   mobile_theme  =  `mobile`
 **/
$config['website']['title'] = 'The Pool - Mining Evolved';
$config['website']['name'] = 'The Pool';
$config['website']['slogan'] = 'Resistance is futile';
$config['website']['email'] = 'test@example.com';
$config['website']['theme'] = 'mmcFE';
$config['website']['mobile'] = true;
$config['website']['mobile_theme'] = 'mobile';


/**
 * Re-Captcha settings
 * Please read http://www.google.com/recaptcha for details
 **/
$config['recaptcha']['enabled'] = false;
$config['recaptcha']['public_key'] = 'YOUR_PUBLIC_RECAPTCHA_KEY';
$config['recaptcha']['private_key'] = 'YOUR_PRIVATE_RECAPTCHA_KEY';

// Currency system used in this pool, default: `LTC`
$config['currency'] = 'LTC';

// Default transaction fee, added by RPC server, default: 0.1
$config['txfee'] = 0.1;

// Payout a block bonus to block finders, default: 0 (disabled)
// This bonus is paid by the pool operator, it is not deducted from the block payout!
$config['block_bonus'] = 0;


/**
 * Payout sytem in use
 *
 * This will modify some templates and activate the
 * appropriate crons. Only ONE payout system at a time
 * is supported!
 *
 * Available options:
 *   prop: Proportional payout system
 *   pps : Pay Per Share payout system
 *
 * Default:
 *   prop
**/
$config['payout_system'] = 'prop';

// For debugging purposes you can archive shares in the archive_shares table, default: true
$config['archive_shares'] = true;

// URL prefix for block searches, used for block links, default: `http://explorer.litecoin.net/search?q=`
$config['blockexplorer'] = 'http://explorer.litecoin.net/search?q=';

// Link to blockchain information, used for difficulty link, default: `http://allchains.info`
// If empty, the difficulty link to the chain information will be removed
$config['chaininfo'] = 'http://allchains.info';

// Pool fees applied to users in percent, default: 0 (disabled)
$config['fees'] = 0;

// Pool target difficulty as set in pushpoold configuration file
// Please also read this for stratum: https://github.com/TheSerapher/php-mmcfe-ng/wiki/FAQ
$config['difficulty'] = 20;


/**
 * This defines how rewards are paid to users.
 *
 * Available options:
 *   fixed : Fixed value according to `reward` setting
 *   block : Dynamic value based on block amount
 *
 * Default:
 *   fixed
 **/
$config['reward_type'] = 'fixed';
$config['reward'] = 50;

// Confirmations per block required to credit transactions, default: 120
$config['confirmations'] = 120;


/**
 * Memcache configuration
 *
 * Please note that a memcache is greatly increasing performance
 * when combined with the `statistics.php` cronjob. Disabling this
 * is not recommended in a live environment!
 *
 * Explanations
 *   keyprefix   :   Must be changed for multiple mmcfe-ng instances on one host
 *   expiration  :   Default expiration time in seconds of all cached keys.
 *                   Increase if caches expire too fast.
 *   splay       :   Default randomizer for expiration times.
 *                   This will spread expired keys across `splay` seconds.
 *
 * Default:
 *   enabled     =  `true`
 *   host        =  `localhost`
 *   port        =  11211
 *   keyprefix   =  `mmcfe_ng_`
 *   expiration  =  90
 *   splay       =  15
 **/
$config['memcache']['enabled'] = true;
$config['memcache']['host'] = 'localhost';
$config['memcache']['port'] = 11211;
$config['memcache']['keyprefix'] = 'mmcfe_ng_';
$config['memcache']['expiration'] = 90;
$config['memcache']['splay'] = 15;


/**
 * Cookie configiration
 *
 * For multiple installations of this cookie change the cookie name
 *
 * Default:
 *   path    =  '/'
 *   name    =  'POOLERCOOKIE'
 *   domain  = ''
 **/
$config['cookie']['path'] = '/';
$config['cookie']['name'] = 'POOLERCOOKIE';
$config['cookie']['domain'] = '';

// Disable or enable smarty cache
// This is usually not required, default: 0
$config['cache'] = 0;

?>
