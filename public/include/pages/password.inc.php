<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// csrf token
if ($config['csrf']['enabled'] && !in_array('passreset', $config['csrf']['disabled_forms'])) {
  $token = $csrftoken->getBasic($user->getCurrentIP(), 'resetpass');
  $smarty->assign('CTOKEN', $token);
}
// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
