<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// poke stratum using gettingstarted details -> enotice
if (function_exists('socket_create')) {
  $host = @gethostbyname($config['gettingstarted']['stratumurl']);
  $port = $config['gettingstarted']['stratumport'];
  if (isset($host) and
    isset($port) and
    ($socket=socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) and
    (socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 3, 'usec' => 0))) and
    (@socket_connect($socket, $host, $port)))
  {
    socket_close($socket);
  } else {
    $newerror = array();
    $newerror['name'] = "Stratum information";
    $newerror['description'] = "We tried to poke your Stratum server using your \$config['gettingstarted'] settings but it didn't respond - " . socket_strerror(socket_last_error()) . ".";
    $newerror['configvalue'] = "gettingstarted";
    $newerror['helplink'] = "https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-getting-started";
    $error[] = $newerror;
    $newerror = null;
  }
} else {
  // Connect via fsockopen as fallback
  if (! $fp = @fsockopen($config['gettingstarted']['stratumurl'], $config['gettingstarted']['stratumport'], $errCode, $errStr, 1)) {
    $newerror = array();
    $newerror['name'] = "Stratum information";
    $newerror['description'] = "We tried to poke your Stratum server using your \$config['gettingstarted'] settings but it didn't respond.";
    $newerror['configvalue'] = "gettingstarted";
    $newerror['helplink'] = "https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-getting-started";
    $error[] = $newerror;
    $newerror = null;
  }
  @fclose($fp);
}
