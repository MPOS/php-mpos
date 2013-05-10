<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

$debug->append('Loading Smarty libraries', 2);
define('SMARTY_DIR', BASEPATH . INCLUDE_DIR . '/smarty/libs/');

// Include the actual smarty class file
include(SMARTY_DIR . 'Smarty.class.php');

// We initialize smarty here
$debug->append('Instantiating Smarty Object', 3);
$smarty = new Smarty;

// Assign our local paths
$debug->append('Define Smarty Paths', 3);
$smarty->template_dir = 'templates/' . THEME . '/';
$smarty->compile_dir = 'templates/compile/';

// Optional smarty caching, check Smarty documentation for details
$smarty->caching = $config['cache'];
$smarty->cache_dir = "templates/cache";
?>
