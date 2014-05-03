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

// We need to find our include files so set this properly
define('BASEPATH', dirname(__FILE__) . '/');

/*****************************************************
 * No need to change beyond this point               *
 *****************************************************/

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

// Include our configuration (holding defines for the requires)
require_once(BASEPATH . '../include/bootstrap.php');
require_once(BASEPATH . '../include/version.inc.php');
?>
