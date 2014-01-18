<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// csrf token
if ($config['csrf']['enabled'] && $config['csrf']['options']['sitewide']) {
  $token = $csrftoken->getBasic($user->getCurrentIP(), 'resetaccount');
  $smarty->assign('CTOKEN', $token);
}
// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
