<?php
(SECURITY == "*)WT#&YHfd" && SECHASH_CHECK) ? die("public/index.php -> Set a new SECURITY value to continue") : 0;
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Default classes
require_once(INCLUDE_DIR . '/lib/KLogger.php');
require_once(CLASS_DIR . '/logger.class.php');
require_once(CLASS_DIR . '/debug.class.php');
if ($config['mysql_filter']) {
  require_once(CLASS_DIR . '/strict.class.php');
}
require_once(INCLUDE_DIR . '/database.inc.php');
require_once(INCLUDE_DIR . '/config/memcache_keys.inc.php');
require_once(INCLUDE_DIR . '/config/error_codes.inc.php');

// We need to load these first
require_once(CLASS_DIR . '/base.class.php');
require_once(CLASS_DIR . '/coins/coin_base.class.php');
require_once(CLASS_DIR . '/setting.class.php');
require_once(INCLUDE_DIR . '/version.inc.php');
if (PHP_OS == 'WINNT') require_once(CLASS_DIR . '/memcached.class.php');

// Now decide on which coin class to load and instantiate
if (file_exists(CLASS_DIR . '/coins/coin_' . $config['algorithm'] . '.class.php')) {
  require_once(CLASS_DIR . '/coins/coin_' . $config['algorithm'] . '.class.php');
  $coin = new Coin();
  $coin->setConfig($config);
} else {
  die('Unable to load your coins class definition for ' . $config['algorithm']);
}

// Swiftmailer
require_once(INCLUDE_DIR . '/lib/swiftmailer/swift_required.php');

// Detect device
if ( PHP_SAPI == 'cli') {
  // Create a new compile folder just for crons
  // We call mail templates directly anyway
  $theme = 'cron';
} else {
  // Use configured theme, fallback to default theme
  $setting->getValue('website_theme') ? $theme = $setting->getValue('website_theme') : $theme = 'bootstrap';
}
define('THEME', $theme);

//Required for Smarty
require_once(CLASS_DIR . '/template.class.php');
// Load smarty now that we have our theme defined
require_once(INCLUDE_DIR . '/smarty.inc.php');

// Load everything else in proper order
require_once(CLASS_DIR . '/mail.class.php');
require_once(CLASS_DIR . '/tokentype.class.php');
require_once(CLASS_DIR . '/token.class.php');
require_once(CLASS_DIR . '/payout.class.php');
require_once(CLASS_DIR . '/block.class.php');

// We require the block class to properly grab the round ID
require_once(CLASS_DIR . '/statscache.class.php');

require_once(CLASS_DIR . '/bitcoin.class.php');
require_once(CLASS_DIR . '/bitcoinwrapper.class.php');
require_once(CLASS_DIR . '/monitoring.class.php');
require_once(CLASS_DIR . '/notification.class.php');
require_once(CLASS_DIR . '/user.class.php');
require_once(CLASS_DIR . '/csrftoken.class.php');
require_once(CLASS_DIR . '/invitation.class.php');
require_once(CLASS_DIR . '/share.class.php');
require_once(CLASS_DIR . '/worker.class.php');
require_once(CLASS_DIR . '/statistics.class.php');
require_once(CLASS_DIR . '/transaction.class.php');
require_once(CLASS_DIR . '/roundstats.class.php');
require_once(CLASS_DIR . '/news.class.php');
require_once(CLASS_DIR . '/api.class.php');
require_once(INCLUDE_DIR . '/lib/Michelf/Markdown.php');
require_once(INCLUDE_DIR . '/lib/scrypt.php');

?>
