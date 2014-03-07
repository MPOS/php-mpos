<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

$location = "https://chunkypools.com/login?error=true";

if (!empty($_POST['username']) && !empty($_POST['password'])) {
  if ($user->checkLogin(@$_POST['username'], @$_POST['password']) ) {
    $location = "https://chunkypools.com/account";
  }
}

if (!headers_sent()) header('Location: ' . $location);
exit('<meta http-equiv="refresh" content="0; url='. $location .'"/>');
?>
