<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

/**
 * Do not edit this unless you have confirmed that your config has been updated!
 *  https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-config-version
 **/
$config['version'] = '0.0.7';

/**
 * Unless you disable this, we'll do a quick check on your config first.
 *  https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-config-check
 */
$config['skip_config_tests'] = false;

/**
 * Defines
 *  Debug setting and salts for hashing passwords
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-defines--salts
 * 24λ�����������Ȼ�����Լ���д�������ֶΣ���Сд��ĸ�����֣����������ST���������һ�£�
 * ����ֵ������ͬ��SALT=SALTY
 */
$config['DEBUG'] = 0;
$config['SALT'] = 'PLEASEMAKEMESOMETHINGRANDOM';
$config['SALTY'] = 'THISSHOULDALSOBERRAANNDDOOM';

/**
  * Coin Algorithm
  *  Algorithm used by this coin, sha256d or scrypt
  *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-algorithm
  * ���ֵ��㷨
  **/
$config['algorithm'] = 'scrypt';

/**
 * Database configuration
 *  MySQL database configuration
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-database-configuration
 * �������ݿ������
 **/
$config['db']['host'] = 'localhost';
$config['db']['user'] = 'someuser';
$config['db']['pass'] = 'somepass';
$config['db']['port'] = 3306;
$config['db']['name'] = 'mpos';

/**
 * Local wallet RPC
 *  RPC configuration for your daemon/wallet
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-local-wallet-rpc
 * ����Ǯ��������
 **/
$config['wallet']['type'] = 'http';
$config['wallet']['host'] = 'localhost:19334';
$config['wallet']['username'] = 'testnet';
$config['wallet']['password'] = 'testnet';

/**
 * Cold Wallet / Liquid Assets
 *  Automatically send liquid assets to a cold wallet
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-liquid-assets--cold-wallet
 *��ǮǮ�������ã��°汾û�ˣ���֪��ת�Ƶ�����ȥ�ˡ�������������������
 **/
#$config['coldwallet']['address'] = '';
#$config['coldwallet']['reserve'] = 50;
#$config['coldwallet']['threshold'] = 5;

/**
 * Getting Started Config
 *  Shown to users in the 'Getting Started' section
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-getting-started
 * �ҵĽ��ܣ�Ǯ�����ƣ��ٷ���ַ���ڿ��ַ��Ĭ����stЭ��ģ����ڿ�˿ں�
 **/
$config['gettingstarted']['coinname'] = 'Litecoin';
$config['gettingstarted']['coinurl'] = 'http://www.litecoin.org';
$config['gettingstarted']['stratumurl'] = '';
$config['gettingstarted']['stratumport'] = '3333';

/**
 * Ticker API
 *  Fetch exchange rates via an API
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-ticker-api
 * �һ��ʻ��߽������������ã�Ŀǰ���治ת��������Ȼ�˹ٷ�֧�ֵĽ�����ֱ�Ӹ��������ֵ�����ˣ��ҵıʼǱ���������BTER��
 **/
$config['price']['url'] = 'https://btc-e.com';
$config['price']['target'] = '/api/2/ltc_usd/ticker';
$config['price']['currency'] = 'USD';

/**
 * Automatic Payout Thresholds
 *  Minimum and Maximum auto payout amount
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-automatic-payout-thresholds
 * �Զ�������С�޶Ⱥ�����޶�
 **/
$config['ap_threshold']['min'] = 1;
$config['ap_threshold']['max'] = 250;

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
 * �ҵ�λ�����
 */
$config['currency'] = 'LTC';

/**
 * Coin Target
 *  Target time for coins to be generated
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-coin-target
 * �ң�һ����ĳ������ڣ��ö඼��60�룬������ٲο���ǰ�ҹ�������
 **/
$config['cointarget'] = '150';

/**
 * Coin Diff Change
 *  Amount of blocks between difficulty changes
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-coin-diff-change
 * ���Ѷȣ�������ٿ��Բο���ǰ��Դ����src/rpcmining.cpp�ļ�ǰ���У�һ�㶼д�ˣ����ûд�ͱ��ָ�ֵĬ�ϣ�
 * ��Դ����/src/rpcmining.cpp
 * Value GetNetworkHashPS(int lookup, int height) {
 * // If lookup is -1, then use blocks since last difficulty change.
 *     if (lookup <= 0)
 *         lookup = pb->nHeight % 2016 + 1;
 **/
$config['coindiffchangetarget'] = 2016;

/**
 * TX Fees
 *  Fees applied to transactions
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-tx-fees
 * ���������ϵıҷ��������ѣ���������д��0��Ϊ�����������òο�Ǯ���ͻ����ϵ�˵�����󲿷���0���ٲ�����Ҫ0.01
 **/
#$config['txfee_auto'] = 0.1;
#$config['txfee_manual'] = 0.1;
$config['txfee_auto'] = 0.01;
$config['txfee_manual'] = 0.01;

/**
 * Block Bonus
 *  Bonus in coins of block bonus
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-block-bonus
 */
$config['block_bonus'] = 0;


/**
 * Payout System
 *  Payout system chosen
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-payout-system
 * �ҵķֱҷ�ʽ�����б���PPNL�ȣ�PROP����õ�
 **/
$config['payout_system'] = 'prop';

/**
 * Sendmany Support
 *  Enable/Disable Sendmany RPC method
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-sendmany-support
 * ����֪��������
 **/
$config['sendmany']['enabled'] = false;

/**
 * Transaction Limits
 *  Number of transactions per payout run
 * �����޶������һ�ηֱ����ֶ��ٴε���˼�����廹��Ҫ��ĥ��������
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
 * �����ȡ�ķ��ã��ٷֱȣ�%
 */
#$config['fees'] = 0;
$config['fees'] = 1;

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
 * ����Ѷȣ����ֵ�����ST������ֵ�γɶ�Ӧ��ϵ������ɲο�������ַ��
 * һ�㳣���ǣ�ST 16 ���� 20��ST 32 ���� 21��ST 64 ���� 22������˵����ST��ֵ�η��������ϵ��Ļ�������ͼ�1
 * һ��100Mһ�£�����20������������ܴ���ô�ͻ���ߵ�ǰ���Ѷȣ���ôST��þͷ���������Ҳ��1����˿�����Ӧ���������Ҳ����������
 * ���壬��Ҫ����ʵ���������
 * ��ֵ�ڽ������ǰ��һ��̶��Ͳ����ˣ����Ҫ�ģ���ô��Ҫ�������ݿ��������û����Ѷ�Ϊ���Ѷȣ�ST�ڵĻ���Ҳ��Ҫ����Ϊ���Ѷȣ�����Ч
 */
$config['difficulty'] = 20;

/**
 * Block Reward
 *  Block reward configuration details
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-reward-settings
 * һ�����ڵı�����һ�㲻����������Ҳ���Ե���������Ҳ��֪������
 **/
$config['reward_type'] = 'block';
$config['reward'] = 50;

/**
 * Confirmations
 *  Credit and Network confirmation settings
 *   https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-confirmations
 * һ������Ҫ�������Ǯ�����յĴ�����һ����Ĭ�Ͼ����ˣ��еı���Ҫȷ�ϵ������٣���ôҲ���Ե��ͣ�������Ҫ�ο���˵��
 */
$config['confirmations'] = 120;
$config['network_confirmations'] = 120;

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
 * ����õĻ��棬һ�㲻����������Ƕ��صĻ���һ��Ҫ�ֶ����ӱ�����������
 * ���磺Ĭ����mpos_�����������Ϊ�˲��໥Ӱ�죬��ô�͸ĳ�mpos_�Ҽ�ƣ�����
 **/
$config['memcache']['enabled'] = true;
$config['memcache']['host'] = 'localhost';
$config['memcache']['port'] = 11211;
$config['memcache']['keyprefix'] = 'mpos_';
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
$config['system']['load']['max'] = 10.0;

?>
