<?php
(SECURITY == "*)WT#&YHfd" && SECHASH_CHECK) ? die("public/index.php -> Set a new SECURITY value to continue") : 0;
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// SHA/Scrypt check
if (empty($config['algorithm']) || $config['algorithm'] == 'scrypt') {
  $config['target_bits'] = 16;
} else {
  $config['target_bits'] = 32;
}

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

// We need to load these two first
require_once(CLASS_DIR . '/base.class.php');
require_once(CLASS_DIR . '/setting.class.php');

// We need this one in here to properly set our theme
require_once(INCLUDE_DIR . '/lib/Mobile_Detect.php');

// Detect device
if ($detect->isMobile() && $setting->getValue('website_mobile_theme')) {
  // Set to mobile theme
  $setting->getValue('website_mobile_theme') ? $theme = $setting->getValue('website_mobile_theme') : $theme = 'mobile';
} else if ( PHP_SAPI == 'cli') {
  // Create a new compile folder just for crons
  // We call mail templates directly anyway
  $theme = 'cron';
} else {
  // Use configured theme, fallback to default theme
  $setting->getValue('website_theme') ? $theme = $setting->getValue('website_theme') : $theme = 'mpos';
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
