<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($user->isAuthenticated()) {
  // Check if we want a specific round or general stats
  if (!empty($_REQUEST['round'])) $round = $_REQUEST['round'];

  // Cache detection and content generation
  if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
    $debug->append('No cached version available, fetching from backend', 3);

  } else {
    $debug->append('Using cached page', 3);
  }
  $smarty->assign("CONTENT", "default.tpl");
}
?>
