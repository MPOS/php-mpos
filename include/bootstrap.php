<?php 
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;
// Used for performance calculations
$dStartTime = microtime(true);

define('INCLUDE_DIR', BASEPATH . '../include');
define('CLASS_DIR', INCLUDE_DIR . '/classes');
define('PAGES_DIR', INCLUDE_DIR . '/pages');
define('TEMPLATE_DIR', BASEPATH . '../templates');

$quickstartlink = "<a href='https://github.com/MPOS/php-mpos/wiki/Quick-Start-Guide' title='MPOS Quick Start Guide'>Quick Start Guide</a>";

// Include our configuration (holding defines for the requires)
if (!include_once(INCLUDE_DIR . '/config/global.inc.dist.php')) die('Unable to load base global config from ['.INCLUDE_DIR. '/config/global.inc.dist.php' . '] - '.$quickstartlink);
if (!@include_once(INCLUDE_DIR . '/config/global.inc.php')) die('Unable to load your global config from ['.INCLUDE_DIR. '/config/global.inc.php' . '] - '.$quickstartlink);

// load our security configs
if (!include_once(INCLUDE_DIR . '/config/security.inc.dist.php')) die('Unable to load base security config from ['.INCLUDE_DIR. '/config/security.inc.dist.php' . '] - '.$quickstartlink);
if (@file_exists(INCLUDE_DIR . '/config/security.inc.php')) include_once(INCLUDE_DIR . '/config/security.inc.php');

// start our session, we need it for smarty caching
session_set_cookie_params(time()+$config['cookie']['duration'], $config['cookie']['path'], $config['cookie']['domain'], $config['cookie']['secure'], $config['cookie']['httponly']);
$session_start = @session_start();
if (!$session_start) {
  $log->log("info", "Forcing session id regeneration, session failed to start [hijack attempt?]");
  session_destroy();
  session_regenerate_id(true);
  session_start();
}
@setcookie(session_name(), session_id(), time()+$config['cookie']['duration'], $config['cookie']['path'], $config['cookie']['domain'], $config['cookie']['secure'], $config['cookie']['httponly']);

// Set the timezone if a user has it set, default UTC
if (isset($_SESSION['USERDATA']['timezone'])) {
  $aTimezones = DateTimeZone::listIdentifiers();
  date_default_timezone_set($aTimezones[$_SESSION['USERDATA']['timezone']]);
} else {
  date_default_timezone_set('UTC');
}

// Our default template to load, pages can overwrite this later
$master_template = 'master.tpl';

// Load Classes, they name defines the $ variable used
// We include all needed files here, even though our templates could load them themself
require_once(INCLUDE_DIR . '/autoloader.inc.php');

?>
