<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// check if memcache isn't available but enabled in config -> error
if (!class_exists('Memcached') && $config['memcache']['enabled']) {
  $newerror = array();
  $newerror['name'] = "Memcache Config";
  $newerror['level'] = 3;
  $newerror['extdesc'] = "Memcache is a service that you run that lets us cache commonly used data and access it quickly. It's highly recommended you <a href='https://github.com/MPOS/php-mpos/wiki/Quick-Start-Guide#requirements-1'>install the service and php packages</a> for your distro.";
  $newerror['description'] = "You have memcached enabled in your config and it's not available as a PHP module. Install the package on your system.";
  $newerror['configvalue'] = "memcache.enabled";
  $newerror['helplink'] = "https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-memcache"; 
  $error[] = $newerror;
  $newerror = null;
}

// if it's not enabled, test it if it exists, if it works -> error tell them to enable, -> otherwise notice it's disabled
if (!$config['memcache']['enabled']) {
  if (PHP_OS == 'WINNT') {
    require_once(CLASS_DIR . 'memcached.class.php');
  }
  if (class_exists('Memcached')) {
    $memcache_test = @new Memcached();
    if ($config['memcache']['sasl'] === true) {
      $memcache_test->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
      $memcache_test->setSaslAuthData($config['memcache']['sasl']['username'], $config['memcache']['sasl']['password']);
    }
    $memcache_test_add = @$memcache_test->addServer($config['memcache']['host'], $config['memcache']['port']);
    $randmctv = rand(5,10);
    $memcache_test_set = @$memcache_test->set('test_mpos_setval', $randmctv);
    $memcache_test_get = @$memcache_test->get('test_mpos_setval');
  }
  if (class_exists('Memcached') && $memcache_test_get == $randmctv) {
    $newerror = array();
    $newerror['name'] = "Memcache Config";
    $newerror['level'] = 2;
    $newerror['extdesc'] = "Memcache is a service that you run that lets us cache commonly used data and access it quickly. It's highly recommended you <a href='https://github.com/MPOS/php-mpos/wiki/Quick-Start-Guide#requirements-1'>install the service and php packages</a> for your distro.";
    $newerror['description'] = "You have memcache disabled in the config but it's available and works! Enable it for best performance.";
    $newerror['configvalue'] = "memcache.enabled";
    $newerror['helplink'] = "https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-memcache"; 
    $error[] = $newerror;
    $newerror = null;
  } else {
    $newerror = array();
    $newerror['name'] = "Memcache Config";
    $newerror['level'] = 2;
     $newerror['extdesc'] = "Memcache is a service that you run that lets us cache commonly used data and access it quickly. It's highly recommended you <a href='https://github.com/MPOS/php-mpos/wiki/Quick-Start-Guide#requirements-1'>install the service and php packages</a> for your distro.";
    $newerror['description'] = "Memcache is disabled; Almost every linux distro has packages for it, you should be using it if you can.";
    $newerror['configvalue'] = "memcache.enabled";
    $newerror['helplink'] = "https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-memcache"; 
    $error[] = $newerror;
    $newerror = null;
  }
}

// check anti DOS protection, we need memcache for that
if ($config['mc_antidos'] && !$config['memcache']['enabled']) {
  $newerror = array();
  $newerror['name'] = "Memcache Config";
  $newerror['level'] = 3;
   $newerror['extdesc'] = "Memcache is a service that you run that lets us cache commonly used data and access it quickly. It's highly recommended you <a href='https://github.com/MPOS/php-mpos/wiki/Quick-Start-Guide#requirements-1'>install the service and php packages</a> for your distro.";
  $newerror['description'] = "mc_antidos is enabled and memcache is not, <u>memcache is required</u> to use this.";
  $newerror['configvalue'] = "memcache.enabled";
  $newerror['helplink'] = "https://github.com/MPOS/php-mpos/wiki/Config-Setup#memcache-rate-limiting"; 
  $error[] = $newerror;
  $newerror = null;
}
