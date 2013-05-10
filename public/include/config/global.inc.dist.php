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
define('DEBUG', 5);

define('SALT', 'LJKEHFuhgu7%&¤Hg783tr7gf¤%¤fyegfredfoGHYFGYe(%/(&%6');

$config = array(
  'difficulty' => '31',         // Target difficulty for this pool
  'reward' => '50',             // Reward for finding blocks
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
