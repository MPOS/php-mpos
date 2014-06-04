<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

/**
 * Do not edit this unless you have confirmed that your config has been updated!
 * Also the URL to check for the most recent upstream versions available
 *  https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-config-version
 **/
$config['version'] = '0.0.8';
$config['version_url'] = 'https://raw.githubusercontent.com/MPOS/php-mpos/master/include/version.inc.php';

/**
 * Unless you disable this, we'll do a quick check on your config first.
 *  https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-config-check
 */
$config['skip_config_tests'] = false;

/**
 * Defines
 *  Debug setting and salts for hashing passwords
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-defines--salts
 */
$config['DEBUG'] = 0;
$config['SALT'] = 'asaadfkugasdifuzga37r47tawrkuzgaf';
$config['SALTY'] = 'asaadfkugasdifuzga37r47tawrkuzgaf';

/**
  * Coin Algorithm
  *  Algorithm used by this coin, sha256d or scrypt
  *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-algorithm
  **/
$config['algorithm'] = 'sha256d';

/**
  * Getbalance API Calls
  *  System used for getting actual Balance from Wallet
  *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#getbalance-api-calls
  **/
$config['getbalancewithunconfirmed'] = true;

/**
 * Database configuration
 *  MySQL database configuration
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-database-configuration
 **/
$config['db']['host'] = 'localhost';
$config['db']['user'] = 'mpos';
$config['db']['pass'] = 'hwv63ahewSz2deJV';
$config['db']['port'] = 3306;
$config['db']['name'] = 'mpos_test';

/**
 * Local wallet RPC
 *  RPC configuration for your daemon/wallet
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-local-wallet-rpc
 **/
$config['wallet']['type'] = 'http';
$config['wallet']['host'] = 'localhost:19011';
$config['wallet']['username'] = 'admin2';
$config['wallet']['password'] = '123';

/**
 * Swiftmailer configuration
 *  Configure your way to send mails
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-swiftmailer
 **/
$config['swiftmailer']['type'] = 'sendmail';
$config['swiftmailer']['sendmail']['path'] = '/usr/sbin/sendmail';
$config['swiftmailer']['sendmail']['options'] = '-bs';
$config['swiftmailer']['smtp']['host'] = 'your.mail-relay.com';
$config['swiftmailer']['smtp']['port'] = '587';
$config['swiftmailer']['smtp']['encryption'] = 'tls';
$config['swiftmailer']['smtp']['username'] = '';
$config['swiftmailer']['smtp']['password'] = '';
$config['switfmailer']['smtp']['throttle'] = 100;

/**
 * Getting Started Config
 *  Shown to users in the 'Getting Started' section
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-getting-started
 **/
$config['gettingstarted']['coinname'] = 'Beercoin';
$config['gettingstarted']['coinurl'] = 'http://beercoin.net';
$config['gettingstarted']['stratumurl'] = 'beercoin.auxmining.de';
$config['gettingstarted']['stratumport'] = '4444';

/**
 * Ticker API
 *  Fetch exchange rates via an API
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-ticker-api
 **/
$config['price']['enabled'] = true;
$config['price']['url'] = 'https://cryptorush.in';
$config['price']['target'] = '/api.php?get=market&m=BEER&b=LTC&key=0c6d100353fb31d58a66631d231b53adf5d53c6c&id=34153&json=true';
$config['price']['currency'] = 'LTC';

/**
 * Automatic Payout Thresholds
 *  Minimum and Maximum auto payout amount
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-automatic-payout-thresholds
 **/
$config['ap_threshold']['min'] = 1;
$config['ap_threshold']['max'] = 5000;

/**
 * Minimum manual Payout Threshold
 *  Minimum manual payout amount
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#minimum-manual-payout-threshold
 **/
$config['mp_min_threshold'] = 1;

/**
 * Maximum Coins per Payout
 *  Maximum Amount of Coins per Payout
 *  Should be set to whatever Coin Daemon can handle as max Transaction for payouts
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#maximum-coins-per-payout
 **/
$config['max_payout_amount'] = 20000;

/**
 * Donation thresholds
 *  Minimum donation amount in percent
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-donation-thresholds
 **/
$config['donate_threshold']['min'] = 1;

/**
 * Account Specific Settings
 *  Settings for each user account
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-account-specific-settings
 **/
$config['accounts']['invitations']['count'] = 5;

/**
 * Currency
 *  Shorthand name for the currency
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-currency
 */
$config['currency'] = 'BEER';

/**
 * Coin Target
 *  Target time for coins to be generated
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-coin-target
 **/
$config['cointarget'] = '60';

/**
 * Coin Diff Change
 *  Amount of blocks between difficulty changes
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-coin-diff-change
 **/
$config['coindiffchangetarget'] = 60;

/**
 * TX Fees
 *  Fees applied to transactions
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-tx-fees
 **/
$config['txfee_auto'] = 0.1;
$config['txfee_manual'] = 0.1;

/**
 * Block & Pool Bonus
 *  Bonus coins for blockfinder or a pool bonus for everyone
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-block-bonus
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-pool-bonus
 */
$config['block_bonus'] = 0;
$config['pool_bonus'] = 0;
$config['pool_bonus_type'] = 'payout';

/**
 * Payout System
 *  Payout system chosen
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-payout-system
 **/
$config['payout_system'] = 'pplns';

/**
 * Sendmany Support
 *  Enable/Disable Sendmany RPC method
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-sendmany-support
 **/
$config['sendmany']['enabled'] = true;

/**
 * Transaction Limits
 *  Number of transactions per payout run
 **/
$config['payout']['txlimit_manual'] = 500;
$config['payout']['txlimit_auto'] = 500;

/**
 * Round Purging
 *  Round share purging configuration
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-round-purging
 **/
$config['purge']['sleep'] = 1;
$config['purge']['shares'] = 25000;

/**
 * Share Archiving
 *  Share archiving configuration details
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-archiving
 **/
$config['archive']['maxrounds'] = 10; 
$config['archive']['maxage'] = 60 * 24; 


/**
 * Pool Fees
 *  Fees applied to users
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-pool-fees
 */
$config['fees'] = 0;

/**
 * PPLNS
 *  Pay Per Last N Shares
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-pplns-settings
 */
$config['pplns']['shares']['default'] = 4000000;
$config['pplns']['shares']['type'] = 'blockavg';
$config['pplns']['blockavg']['blockcount'] = 10;
$config['pplns']['reverse_payout'] = false;
$config['pplns']['dynamic']['percent'] = 30;

/**
 * Difficulty
 *  Difficulty setting for stratum/pushpool
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-pool-target-difficulty
 */
$config['difficulty'] = 20;

/**
 * Block Reward
 *  Block reward configuration details
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-reward-settings
 **/
$config['reward_type'] = 'block';
$config['reward'] = 50;

/**
 * Confirmations
 *  Credit and Network confirmation settings
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-confirmations
 */
$config['confirmations'] = 30;
$config['network_confirmations'] = 30;

/**
 * PPS
 *  Pay Per Share configuration details
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-pps-settings
 **/
$config['pps']['reward']['default'] = 50;
$config['pps']['reward']['type'] = 'blockavg';
$config['pps']['blockavg']['blockcount'] = 10;

/**
 * Memcache
 *  Memcache configuration details
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-memcache
 **/
$config['memcache']['enabled'] = false;
$config['memcache']['host'] = 'localhost';
$config['memcache']['port'] = 11211;
$config['memcache']['keyprefix'] = 'mpos_beer';
$config['memcache']['expiration'] = 90;
$config['memcache']['splay'] = 15;
$config['memcache']['force']['contrib_shares'] = false;

/**
 * Cookies
 *  Cookie configuration details
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-cookies
 **/
$config['cookie']['duration'] = '1440';
$config['cookie']['domain'] = '';
$config['cookie']['path'] = '/';
$config['cookie']['httponly'] = true;
$config['cookie']['secure'] = false;

/**
 * Smarty Cache
 *  Enable smarty cache and cache length
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-smarty-cache
 **/
$config['smarty']['cache'] = 0;
$config['smarty']['cache_lifetime'] = 30;

/**
 * System load
 *  Disable some calls when high system load
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-system-load
 **/
$config['system']['load']['max'] = 80.0;

?>
