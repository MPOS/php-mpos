<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check if the API is activated
$api->isActive();

// Check for valid API key
$id = $user->checkApiKey($_REQUEST['api_key']);

header('HTTP/1.1 400 Bad Request');
die('400 Bad Request');
?>
