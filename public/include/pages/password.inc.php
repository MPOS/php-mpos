<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

// csrf token
if ($config['csrf']['enabled'] && $config['csrf']['options']['sitewide']) {
  // we have to use editaccount token because this can be called from 2 places
  $token = $csrftoken->getBasic($user->getCurrentIP(), 'editaccount', 'mdyH');
  $smarty->assign('CTOKEN', $token);
}
// Tempalte specifics
$smarty->assign("CONTENT", "default.tpl");
?>
