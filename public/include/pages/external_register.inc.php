<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

isset($_POST['token']) ? $token = $_POST['token'] : $token = '';
if ($user->register(@$_POST['username'], @$_POST['password1'], @$_POST['password2'], @$_POST['pin'], @$_POST['email1'], @$_POST['email2'], @$_POST['tac'], $token)) {
  $location = "https://chunkypools.com/login?register=true";
} else {
  $message = $user->getError();
  $location = "https://chunkypools.com/register?error=true&message=$message";
}

if (!headers_sent()) header('Location: ' . $location);
exit('<meta http-equiv="refresh" content="0; url='. $location .'"/>');

?>

