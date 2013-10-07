<?php
// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Our include directory for additional features
define('INCLUDE_DIR', BASEPATH . 'include');

// Our class directory
define('CLASS_DIR', INCLUDE_DIR . '/classes');

// Our pages directory which takes care of
define('PAGES_DIR', INCLUDE_DIR . '/pages');

// Our theme folder holding all themes
define('THEME_DIR', BASEPATH . 'templates');

// Set debugging level for our debug class
define('DEBUG', 0);

// SALT used to hash passwords
define('SALT', 'PLEASEMAKEMESOMETHINGRANDOM');

/**
 * Database configuration
 *
 * A MySQL database backend is required for MPOS.
 * Also ensure the database structure is imported!
 * The SQL file should be included in this project under the `sql` directory
 *
 * Default:
 *   host     =  'localhost'
 *   port     =  3306
 *   user     =  'someuser'
 *   pass     =  'somepass'
 *   name     =  'mpos'
 **/
$config['db']['host'] = 'localhost';
$config['db']['user'] = 'someuser';
$config['db']['pass'] = 'somepass';
$config['db']['port'] = 3306;
$config['db']['name'] = 'mpos';

/**
 * Local wallet RPC configuration
 *
 * MPOS uses the RPC backend to fetch transactions, blocks
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
 *   MPOS will try to fetch the current exchange rates
 *   from this API URL/target. Currently btc-e and coinchoose
 *   are supported in MPOS. If you want to remove the trade
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
 *
 * Optional (cryptsy.com):
 *   url       =  `http://pubapi.cryptsy.com`
 *   currency  =  `BTC`
 *   target    =  `/api.php?method=marketdata`
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
 * Account specific settings
 *
 * Explanation
 *   Invitations will allow your users to invite new members to join the pool.
 *   After sending a mail to the invited user, they can register using the token
 *   created. Invitations can be enabled and disabled through the admin panel.
 *   Sent invitations are listed on the account invitations page.
 *
 *   You can limit the number of registrations send per account via configuration
 *   variable.
 *
 *  Options:
 *    count          :  Maximum invitations a user is able to send
 *
 *  Defaults:
 *    count          :  5
 **/
$config['accounts']['invitations']['count'] = 5;

// Currency system used in this pool, default: `LTC`
$config['currency'] = 'LTC';

/**
 * Default transaction fee to apply to user transactions
 *
 * Explanation
 *   The coin daemon applies transcation fees to young coins.
 *   Since we are unable to find out what the exact fee was we set
 *   a default value here which is applied to both manual and auto payouts.
 *   If this is not set, no fee is applied in the transactions history but
 *   the user might still see them when the coins arrive.
 *
 * Default:
 *   txfee   =  0.1
 **/
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
 *   pplns : Pay Per Last N Shares payout system
 *
 * Default:
 *   prop
**/
$config['payout_system'] = 'prop';

/**
 * Archiving configuration for debugging
 *
 * Explanation:
 *   By default, we don't need to archive for a long time. PPLNS and Hashrate
 *   calculations rely on this archive, but all shares past a certain point can
 *   safely be deleted.
 *
 *   To ensure we have enough shares on stack for PPLNS, this
 *   is set to the past 10 rounds. Even with lucky ones in between those should
 *   fit the PPLNS target. On top of that, even if we have more than 10 rounds,
 *   we still keep the last maxage shares to ensure we can calculate hashrates.
 *   Both conditions need to be met in order for shares to be purged from archive.
 *
 *   Proportional mode will only keep the past 24 hours. These are required for
 *   hashrate calculations to work past a round, hence 24 hours was selected as
 *   the default. You may want to increase the time for debugging, then add any
 *   integer reflecting minutes of shares to keep.
 *
 * Availabe Options:
 *   maxrounds  :  PPLNS, keep shares for maxrounds
 *   maxage     :  PROP and PPLNS, delete shares older than maxage minutes
 *
 * Default:
 *   maxrounds  =  10
 *   maxage     =  60 * 24   (24h)
 **/
$config['archive']['maxrounds'] = 10; 
$config['archive']['maxage'] = 60 * 24; 

// Pool fees applied to users in percent, default: 0 (disabled)
$config['fees'] = 0;

/**
  *  PPLNS requires some settings to run properly. First we need to define
  *  a default shares count that is applied if we don't have a proper type set.
  *  Different dynamic types can be applied, or you can run a fixed scheme.
  *
  *  Explanation
  *
  *   PPLNS can run on two different payouts: fixed and blockavg. Each one
  *   defines a different PPLNS target.
  *
  *   Fixed means we will be looking at the shares setup in the default
  *   setting. There is no automatic adjustments to the PPLNS target,
  *   all users will be paid out proportionally to that target.
  *
  *   Blockavg will look at the last blockcount blocks shares and take
  *   the average as the PPLNS target. This will be automatically adjusted
  *   when difficulty changes and more blocks are available. This keeps the
  *   target dynamic but still traceable.
  *
  *   If you use the fixed type it will use $config['pplns']['shares']['default']
  *   for target calculations, if you use blockavg type it will use 
  *   $config['pplns']['blockavg']['blockcount'] blocks average for target
  *   calculations.
  *
  *   default     :  Default target shares for PPLNS
  *   type        :  Payout type used in PPLNS
  *   blockcount  :  Amount of blocks to check for avg shares
  *
  *  Available Options:
  *   default     :  amount of shares, integeger
  *   type        :  blockavg or fixed
  *   blockcount  :  amount of blocks, any integer
  *
  *  Defaults:
  *   default     =  4000000
  *   type        =  `blockavg`
  *   blockcount  =  10
  **/
$config['pplns']['shares']['default'] = 4000000;
$config['pplns']['shares']['type'] = 'blockavg';
$config['pplns']['blockavg']['blockcount'] = 10;

// Pool target difficulty as set in pushpoold configuration file
// Please also read this for stratum: https://github.com/TheSerapher/php-mpos/wiki/FAQ
$config['difficulty'] = 20;


/**
 * This defines how rewards are paid to users.
 *
 * Explanation:
 *
 *  Proportional + PPLNS Payout System
 *   When running a pool on fixed mode, each block will be paid
 *   out as defined in `reward`. If you wish to pass transaction
 *   fees inside discovered blocks on to user, set this to `block`.
 *   This is really helpful for altcoins with dynamic block values!
 *
 *  PPS Payout System
 *   If set to `fixed`, all PPS values are based on the `reward` setting.
 *   If you set it to `block` you will calculate the current round based
 *   on the previous block value. The idea is to pass the block of the
 *   last round on to the users. If no previous block is found, PPS value
 *   will fall back to the fixed value set in `reward`. Ensure you don't
 *   overpay users in the first round!
 *
 * Available options:
 *  reward_type:
 *   fixed : Fixed value according to `reward` setting
 *   block : Dynamic value based on block amount
 *  reward:
 *   float value : Any value of your choice but should reflect base block values
 *
 * Default:
 *   reward_type  = `fixed`
 *   reward       = 50
 *
 **/
$config['reward_type'] = 'fixed';
$config['reward'] = 50;

// Confirmations per block required to credit transactions, default: 120
$config['confirmations'] = 120;
// Confirmations per block required in network to confirm its transactions, default: 120
$config['network_confirmations'] = 120;

 /**
 * Available pps options:
 *  reward_type:
 *   fixed : Fixed value according to `reward` setting
 *   blockavg : Dynamic value based on average of x number of block rewards
 *   block : Dynamic value based on LAST block amount
 *  reward:
 *   float value : Any value of your choice but should reflect base block values
 *   blockcount  :  amount of blocks to average, any integer
 * Default:
 *   pps_reward_type  = `fixed` default $config['pps']['reward']['default']
 *   reward       = 50
 *
 **/
$config['pps']['reward']['default'] = 50;
$config['pps']['reward']['type'] = 'blockavg';
$config['pps']['blockavg']['blockcount'] = 10;

// pps base payout target, default 16 = difficulty 1 shares for vardiff
// (1/(65536 * difficulty) * reward) = (reward / (pow(2,32) * difficulty) * pow(2, 16))
$config['pps_target'] = 16; // do not change unless you know what it does

/**
 * Memcache configuration
 *
 * To disable memcache set option $config['memcache']['enabled'] = false
 * After disable memcache installation of memcache is not required.
 *
 * Please note that a memcache is greatly increasing performance
 * when combined with the `statistics.php` cronjob. Disabling this
 * is not recommended in a live environment!
 *
 * Explanations
 *   enabled     :   Disable (false) memcache for debugging or enable (true) it
 *   host        :   Host IP or hostname
 *   port        :   memcache port
 *   keyprefix   :   Must be changed for multiple MPOS instances on one host
 *   expiration  :   Default expiration time in seconds of all cached keys.
 *                   Increase if caches expire too fast.
 *   splay       :   Default randomizer for expiration times.
 *                   This will spread expired keys across `splay` seconds.
 *
 * Default:
 *   enabled     =  `true`
 *   host        =  `localhost`
 *   port        =  11211
 *   keyprefix   =  `mpos_`
 *   expiration  =  90
 *   splay       =  15
 **/
$config['memcache']['enabled'] = true;
$config['memcache']['host'] = 'localhost';
$config['memcache']['port'] = 11211;
$config['memcache']['keyprefix'] = 'mpos_';
$config['memcache']['expiration'] = 90;
$config['memcache']['splay'] = 15;


/**
 * Cookie configiration
 *
 * You can configure the cookie behaviour to secure your cookies more than the PHP defaults
 *
 * For multiple installations of MPOS on the same domain you must change the cookie path.
 *
 * Explanation:
 * duration:
 *   the amount of time, in seconds, that a cookie should persist in the users browser.
 *   0 = until closed; 1440 = 24 minutes. Check your php.ini 'session.gc_maxlifetime' value
 *   and ensure that it is at least the duration specified here.
 *
 * domain:
 *   the only domain name that may access this cookie in the browser
 *
 * path:
 *   the highest path on the domain that can access this cookie; i.e. if running two pools
 *   from a single domain you might set the path /ltc/ and /ftc/ to separate user session
 *   cookies between the two.
 *
 * httponly:
 *   marks the cookie as accessible only through the HTTP protocol. The cookie can't be
 *   accessed by scripting languages, such as JavaScript. This can help to reduce identity
 *   theft through XSS attacks in most browsers.
 *
 * secure:
 *   marks the cookie as accessible only through the HTTPS protocol. If you have a SSL
 *   certificate installed on your domain name then this will stop a user accidently
 *   accessing the site over a HTTP connection, without SSL, exposing their session cookie.
 *
 * Default:
 *   duration = '1440'
 *   domain   = ''
 *   path     = '/'
 *   httponly = true
 *   secure   = false
 **/
$config['cookie']['duration'] = '1440';
$config['cookie']['domain'] = '';
$config['cookie']['path'] = '/';
$config['cookie']['httponly'] = true;
$config['cookie']['secure'] = false;

/**
 * Enable or disable the Smarty cache
 *
 * Explanation:
 *   Smarty implements a file based cache for all HTML output generated
 *   from dynamic scripts. It can be enabled to cache the HTML data on disk,
 *   future request are served from those cache files.
 *
 *   This may or may not work as expected, in general Memcache is used to cache
 *   all data so rendering the page should not take too long anyway.
 *
 *   You can test this out and enable (1) this setting but it's not guaranteed to
 *   work with MPOS.
 *
 *   Ensure that the folder `templates/cache` is writable by the webserver!
 *
 *   cache           =  Enable/Disable the cache
 *   cache_lifetime  =  Time to keep files in seconds before updating them
 *
 *  Options:
 *    cache:
 *      0  =  disabled
 *      1  =  enabled
 *    cache_lifetime:
 *      time in seconds
 *
 *  Defaults:
 *    cache           =  0, disabled
 *    cache_lifetime  =  30 seconds
 **/
$config['smarty']['cache'] = 0;
$config['smarty']['cache_lifetime'] = 30;
?>
