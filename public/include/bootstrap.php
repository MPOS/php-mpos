<?php 
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;
// Used for performance calculations
$dStartTime = microtime(true);

define('INCLUDE_DIR', BASEPATH . 'include');
define('CLASS_DIR', INCLUDE_DIR . '/classes');
define('PAGES_DIR', INCLUDE_DIR . '/pages');
define('THEME_DIR', BASEPATH . 'templates');

$quickstartlink = "<a href='https://github.com/MPOS/php-mpos/wiki/Quick-Start-Guide' title='MPOS Quick Start Guide'>Quick Start Guide</a>";

// Include our configuration (holding defines for the requires)
if (!include_once(BASEPATH . 'include/config/global.inc.dist.php')) die('Unable to load base global config - '.$quickstartlink);
if (!@include_once(BASEPATH . 'include/config/global.inc.php')) die('Unable to load your global config - '.$quickstartlink);

// load our security configs
if (!include_once(BASEPATH . 'include/config/security.inc.dist.php')) die('Unable to load base security config - '.$quickstartlink);
if (@file_exists(BASEPATH . 'include/config/security.inc.php')) include_once(BASEPATH . 'include/config/security.inc.php');

// start our session, we need it for smarty caching
$session_start = @session_start();
session_set_cookie_params(time()+$config['cookie']['duration'], $config['cookie']['path'], $config['cookie']['domain'], $config['cookie']['secure'], $config['cookie']['httponly']);
if (!$session_start) {
    $log->log("info", "Forcing session id regeneration, session failed to start [hijack attempt?]");
      session_destroy();
      session_regenerate_id(true);
        session_start();
}
@setcookie(session_name(), session_id(), time()+$config['cookie']['duration'], $config['cookie']['path'], $config['cookie']['domain'], $config['cookie']['secure'], $config['cookie']['httponly']);

// Our default template to load, pages can overwrite this later
$master_template = 'master.tpl';

// Load Classes, they name defines the $ variable used
// We include all needed files here, even though our templates could load them themself
require_once(INCLUDE_DIR . '/autoloader.inc.php');

?>
