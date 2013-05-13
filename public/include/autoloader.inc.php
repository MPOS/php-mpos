<?php

require_once(CLASS_DIR . '/debug.class.php');
require_once(CLASS_DIR . '/bitcoin.class.php');
require_once(INCLUDE_DIR . '/database.inc.php');
require_once(INCLUDE_DIR . '/smarty.inc.php');
// Load classes that need the above as dependencies
require_once(CLASS_DIR . '/block.class.php');
require_once(CLASS_DIR . '/user.class.php');
require_once(CLASS_DIR . '/share.class.php');
require_once(CLASS_DIR . '/worker.class.php');
require_once(CLASS_DIR . '/statistics.class.php');
require_once(CLASS_DIR . '/transaction.class.php');
require_once(CLASS_DIR . '/settings.class.php');

// Use Memcache to store our data
$memcache = new Memcached();
$memcache->addServer('localhost', 11211);
