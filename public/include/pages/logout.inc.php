<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// This probably (?) never fails
$user->logoutUser();
header('Location: index.php?page=home');
?>
