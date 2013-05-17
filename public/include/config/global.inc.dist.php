<?php
// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

// What is our overall theme
define('THEME', 'mmcFE');

// Our include directory for additional features
define('INCLUDE_DIR', BASEPATH . 'include');

// Our class directory
define('CLASS_DIR', INCLUDE_DIR . '/classes');

// Our pages directory which takes care of
define('PAGES_DIR', INCLUDE_DIR . '/pages');

// Set debugging level for our debug class
define('DEBUG', 0);

define('SALT', 'PLEASEMAKEMESOMETHINGRANDOM');

$config = array(
  'website' => array(
    'name' => 'The Pool',
    'slogan' => 'Resistance is futile',
  ),
  'difficulty' => '31',         // Target difficulty for this pool as set in pushpoold json
  'reward' => '50',             // Reward for finding blocks, fixed value but changes someday
  'confirmations' => '120',     // Confirmations per block needed to credit transactions
  'memcache' => array(
    'enabled'   => true,
    'host'      => 'localhost', // Memcache Host
    'post'      => 11211,       // Memcache Port
    'keyprefix' => 'mmcfe_ng_', // Prefix for all keys
    'expiration'=> '90',        // Cache time
    'splay'     => '15'         // Splay time
  ),
  'wallet' => array(
    'type' => 'http',            // http or https are supported
    'host' => 'localhost:9332',
    'username' => 'litecoinrpc',
    'password' => 'somepass'
  ),
  'cashout' => array(
    'min_balance' => 0.0                // Minimal balance to cash out
  ),
  'cookie' => array(
    'path' => '/',
    'name' => 'POOLERCOOKIE',
    'domain' => ''
  ),
  'cache' => 0,    // 1 to enable smarty cache in templates/cache
  'db' => array(
    'host' => 'localhost',
    'user' => 'someuser',
    'pass' => 'somepass',
    'port' => '3306',
    'name' => 'litecoin',
  ),
);
?>
