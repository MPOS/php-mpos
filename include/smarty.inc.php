<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

$debug->append('Loading Smarty libraries', 2);
define('SMARTY_DIR', INCLUDE_DIR . '/smarty/libs/');

// Include the actual smarty class file
include(SMARTY_DIR . 'Smarty.class.php');

// We initialize smarty here
$debug->append('Instantiating Smarty Object', 3);
$smarty = new Smarty;

// Assign our local paths
$debug->append('Define Smarty Paths', 3);
$smarty->template_dir = TEMPLATE_DIR . '/' . THEME . '/';
$smarty->compile_dir = TEMPLATE_DIR . '/compile/' . THEME . '/';
$smarty_cache_key = md5(serialize($_REQUEST) . serialize(@$_SESSION['USERDATA']['id']));

// Optional smarty caching, check Smarty documentation for details
if ($config['smarty']['cache']) {
  $debug->append('Enable smarty cache');
  $smarty->setCaching(Smarty::CACHING_LIFETIME_SAVED);
  $smarty->cache_lifetime = $config['smarty']['cache_lifetime'];
  $smarty->cache_dir = TEMPLATE_DIR . '/cache/' . THEME;
  $smarty->escape_html = true;
  $smarty->use_sub_dirs = true;
}

// Load custom smarty plugins
require_once(INCLUDE_DIR . '/lib/smarty_plugins/function.acl.php');
?>
