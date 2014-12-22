<?php
// This is global bootstrap for autoloading 

// Set a decently long SECURITY key with special chars etc
define('SECURITY', '*)WT#&YHfd');
// Whether or not to check SECHASH for validity, still checks if SECURITY defined as before if disabled
define('SECHASH_CHECK', false);

// change SECHASH every second, we allow up to 3 sec back for slow servers
if (SECHASH_CHECK) {
  function fip($tr=0) { return md5(SECURITY.(time()-$tr).SECURITY); }
  define('SECHASH', fip());
  function cfip() { return (fip()==SECHASH||fip(1)==SECHASH||fip(2)==SECHASH) ? 1 : 0; }
} else {
  function cfip() { return (@defined('SECURITY')) ? 1 : 0; }
}


define("BASEPATH", dirname(__FILE__) . "/");

define('INCLUDE_DIR', BASEPATH . '../include');
define('CLASS_DIR', INCLUDE_DIR . '/classes');
define('PAGES_DIR', INCLUDE_DIR . '/pages');
define('TEMPLATE_DIR', BASEPATH . '../templates');