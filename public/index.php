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
// Set a decently long SECURITY key with special chars etc
define('SECURITY', '*)WT#&YHfd');
// Whether or not to check SECHASH for validity, still checks if SECURITY defined as before if disabled
define('SECHASH_CHECK', false);

// Nothing below here to configure, move along...

// change SECHASH every second, we allow up to 3 sec back for slow servers
if (SECHASH_CHECK) {
  function fip($tr=0) { return md5(SECURITY.(time()-$tr).SECURITY); }
  define('SECHASH', fip());
  function cfip() { return (fip()==SECHASH||fip(1)==SECHASH||fip(2)==SECHASH) ? 1 : 0; }
} else {
  function cfip() { return (@defined('SECURITY')) ? 1 : 0; }
}

// This should be okay
// No but Its now, - Aim
define("BASEPATH", dirname(__FILE__) . "/");

// all our includes and config etc are now in bootstrap
include_once('include/bootstrap.php');

// switch to https if config option is enabled
$hts = ($config['https_only'] && (!empty($_SERVER['QUERY_STRING']))) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?".$_SERVER['QUERY_STRING'] : "https://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
($config['https_only'] && @!$_SERVER['HTTPS']) ? exit(header("Location: ".$hts)):0;

// Rate limiting
if ($config['memcache']['enabled'] && $config['mc_antidos']['enabled']) {
  if (PHP_OS == 'WINNT') {
    require_once(CLASS_DIR . 'memcached.class.php');
  }
  // memcache antidos needs a memcache handle
  $memcache = new Memcached();
  $memcache->addServer($config['memcache']['host'], $config['memcache']['port']);
  require_once(CLASS_DIR . '/memcache_ad.class.php');
  $skip_check = false;
  // if this is an api call we need to be careful not to time them out for those calls separately
  $per_page = '';
  $ajax_calls = array(
    array('api', 'getuserbalance'),
    array('api', 'getnavbardata'),
    array('api', 'getdashboarddata'),
    array('api', 'getuserworkers')
  );
  $iac = 0;
  foreach ($ajax_calls as $ac) {
    $iac = (@$_REQUEST['page'] == $ac[0] && @$_REQUEST['action'] == $ac[1]) ? $iac+=1 : $iac;
  }
  $is_ajax_call = ($iac > 0) ? true : false;
  if ($is_ajax_call && $config['mc_antidos']['protect_ajax']) {
    $per_page = 'api';
  } else if ($is_ajax_call && !$config['mc_antidos']['protect_ajax']) {
    // protect isn't on, we'll ignore it
    $skip_check = true;
  } else if ($config['mc_antidos']['ignore_admins'] && isset($_SESSION['USERDATA']['is_admin']) && $_SESSION['USERDATA']['is_admin']) {
    $skip_check = true;
  }
  if (!$skip_check) {
    $mcad = new MemcacheAntiDos($config, $memcache, $per_page);
    if ($config['mc_antidos']['protect_ajax'] && $is_ajax_call && $mcad->rate_limit_api_request) {
      exit(header('HTTP/1.1 401 Unauthorized'));
    }
    $error_page = $config['mc_antidos']['error_push_page'];
    if ($mcad->rate_limit_site_request) {
      if (!is_array($error_page) || count($error_page) < 1 || (empty($error_page['page']) && empty($error_page['action']))) {
        die("You are sending too many requests too fast!");
      } else {
        $_REQUEST['page'] = $error_page['page'];
        $_REQUEST['action'] = (isset($error_page['action']) && !empty($error_page['action'])) ? $error_page['action'] : $_REQUEST['action'];
      }
    }
  }
}

// Got past rate limiter and session manager
// show last logged in popup if it's still set
if (@$_GET['clp'] == 1 && @$_SESSION['last_ip_pop']) unset($_SESSION['last_ip_pop']);
if (count(@$_SESSION['last_ip_pop']) == 2) {
  $data = $_SESSION['last_ip_pop'];
  $ip = filter_var($data[0], FILTER_VALIDATE_IP);
  $time = date("l, F jS \a\\t g:i a", $data[1]);
  $closelink = "<a href='index.php?page=dashboard&clp=1' style='float:right;padding-right:14px;'>Close</a>";
  if (@$_SESSION['AUTHENTICATED'] && $_SESSION['last_ip_pop'][0] !== $user->getCurrentIP()) {
    $_SESSION['POPUP'][] = array('CONTENT' => "You last logged in from <b>$ip</b> on $time $closelink", 'TYPE' => 'warning');
  } else {
    $_SESSION['POPUP'][] = array('CONTENT' => "You last logged in from <b>$ip</b> on $time $closelink", 'TYPE' => 'info');
  }
}

// version check and config check if not disabled
if (@$_SESSION['USERDATA']['is_admin'] && $user->isAdmin(@$_SESSION['USERDATA']['id'])) {
  require_once(INCLUDE_DIR . '/version.inc.php');
  if (!@$config['skip_config_tests']) {
    require_once(INCLUDE_DIR . '/admin_checks.php');
  }
}

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
@$_REQUEST['page'] = (is_array($_REQUEST['page']) || !isset($_REQUEST['page'])) ? 'home' : $_REQUEST['page'];
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
$action = (isset($_REQUEST['action']) && !is_array($_REQUEST['action'])) && isset($arrActions[$_REQUEST['action']]) ? $_REQUEST['action'] : "";

// Check csrf token validity if necessary
if ($config['csrf']['enabled'] && isset($_REQUEST['ctoken']) && !empty($_REQUEST['ctoken']) && !is_array($_REQUEST['ctoken'])) {
  $csrftoken->valid = ($csrftoken->checkBasic(session_id(), $arrPages[$page], $_REQUEST['ctoken'])) ? 1 : 0;
} else if ($config['csrf']['enabled'] && (!@$_REQUEST['ctoken'] || empty($_REQUEST['ctoken']))) {
  $csrftoken->valid = 0;
}
if ($config['csrf']['enabled']) $smarty->assign('CTOKEN', $csrftoken->getBasic(session_id(), $arrPages[$page]));

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
