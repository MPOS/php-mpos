<?php

/*

Copyright:: 2013, Sebastian Grewe

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

 */

// Used for performance calculations
$dStartTime = microtime(true);

// This should be okay
define("BASEPATH", "./");

// Our security check
define("SECURITY", 1);

// Include our configuration (holding defines for the requires)
if (!include_once(BASEPATH . 'include/config/global.inc.php')) die('Unable to load site configuration');

// Our default template to load, pages can overwrite this later
$master_template = 'master.tpl';

// Start a session
session_set_cookie_params(time()+$config['cookie']['duration'], $config['cookie']['path'], $config['cookie']['domain'], $config['cookie']['secure'], $config['cookie']['httponly']);
session_start();
setcookie(session_name(),session_id(),time()+$config['cookie']['duration'], $config['cookie']['path'], $config['cookie']['domain'], $config['cookie']['secure'], $config['cookie']['httponly']);
$session_id = session_id();

// Load Classes, they name defines the $ variable used
// We include all needed files here, even though our templates could load them themself
require_once(INCLUDE_DIR . '/autoloader.inc.php');

// Create our pages array from existing files
if (is_dir(INCLUDE_DIR . '/pages/')) {
    foreach (glob(INCLUDE_DIR . '/pages/*.inc.php') as $filepath) {
        $filename = basename($filepath);
        $pagename = substr($filename, 0, strlen($filename) - 8);
        $arrPages[$pagename] = $filename;
        $debug->append("Adding $pagename as " . $filename . " to accessible pages", 4);
    }
}

// Set a default action here if no page has been requested
if (isset($_REQUEST['page']) && isset($arrPages[$_REQUEST['page']])) {
  $page = $_REQUEST['page'];
} else if (isset($_REQUEST['page']) && ! isset($arrPages[$_REQUEST['page']])) {
  $page = 'error';
} else {
  $page = 'home';
}

// Create our pages array from existing files
if (is_dir(INCLUDE_DIR . '/pages/' . $page)) {
    foreach (glob(INCLUDE_DIR . '/pages/' . $page . '/*.inc.php') as $filepath) {
        $filename = basename($filepath);
        $pagename = substr($filename, 0, strlen($filename) - 8);
        $arrActions[$pagename] = $filename;
        $debug->append("Adding $pagename as " . $filename . ".inc.php to accessible actions", 4);
    }
}
// Default to empty (nothing) if nothing set or not known
$action = isset($_REQUEST['action']) && isset($arrActions[$_REQUEST['action']]) ? $_REQUEST['action'] : "";

// Load the page code setting the content for the page OR the page action instead if set
if (!empty($action)) {
    $debug->append('Loading Action: ' . $action . ' -> ' . $arrActions[$action], 1);
    require_once(PAGES_DIR . '/' . $page . '/' . $arrActions[$action]);
} else {
    $debug->append('Loading Page: ' . $page . ' -> ' . $arrPages[$page], 1);
    require_once(PAGES_DIR . '/' . $arrPages[$page]);
}

define('PAGE', $page);
define('ACTION', $action);

// For our content inclusion
$smarty->assign("PAGE", $page);
$smarty->assign("ACTION", $action);

// Now with all loaded and processed, setup some globals we need for smarty templates
if ($page != 'api') require_once(INCLUDE_DIR . '/smarty_globals.inc.php');

// Load debug information into template
$debug->append("Loading debug information into template", 4);
$smarty->assign('DebuggerInfo', $debug->getDebugInfo());
$smarty->assign('RUNTIME', (microtime(true) - $dStartTime) * 1000);

// Display our page
if (!@$supress_master) $smarty->display($master_template, $smarty_cache_key);

// Unset any temporary values here
unset($_SESSION['POPUP']);
?>
