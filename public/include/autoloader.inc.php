<?php

// We need this one in here to properly set our theme
require_once(INCLUDE_DIR . '/lib/Mobile_Detect.php');

// Detect device
if ($detect->isMobile() && $config['website']['mobile']) {
  // Set to mobile theme
  $config['website']['mobile_theme'] ? $theme = $config['website']['mobile_theme'] : $theme = 'mobile';
} else {
  // Use configured theme, fallback to default theme
  $config['website']['theme'] ? $theme = $config['website']['theme'] : $theme = 'mmcFE';
}
define('THEME', $theme);

require_once(CLASS_DIR . '/debug.class.php');
require_once(CLASS_DIR . '/bitcoin.class.php');
require_once(CLASS_DIR . '/statscache.class.php');
require_once(CLASS_DIR . '/bitcoinwrapper.class.php');
require_once(INCLUDE_DIR . '/lib/KLogger.php');
require_once(INCLUDE_DIR . '/database.inc.php');
require_once(INCLUDE_DIR . '/smarty.inc.php');
// Load classes that need the above as dependencies
require_once(CLASS_DIR . '/base.class.php');
require_once(CLASS_DIR . '/mail.class.php');
require_once(CLASS_DIR . '/tokentype.class.php');
require_once(CLASS_DIR . '/token.class.php');
require_once(CLASS_DIR . '/block.class.php');
require_once(CLASS_DIR . '/setting.class.php');
require_once(CLASS_DIR . '/monitoring.class.php');
require_once(CLASS_DIR . '/user.class.php');
require_once(CLASS_DIR . '/share.class.php');
require_once(CLASS_DIR . '/worker.class.php');
require_once(CLASS_DIR . '/statistics.class.php');
require_once(CLASS_DIR . '/transaction.class.php');
require_once(CLASS_DIR . '/notification.class.php');
require_once(CLASS_DIR . '/news.class.php');
require_once(INCLUDE_DIR . '/lib/Michelf/Markdown.php');
require_once(INCLUDE_DIR . '/lib/scrypt.php');
