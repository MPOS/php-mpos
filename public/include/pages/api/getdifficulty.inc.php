<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
die('Hacking attempt');

// Check user token
$id = $user->checkApiKey($_REQUEST['api_key']);

// Fetch data from litecoind
if ($bitcoin->can_connect() === true){
  if (!$dDifficulty = $memcache->get('dDifficulty')) {
    $dDifficulty = $bitcoin->query('getdifficulty');
    if (is_array($dDifficulty) && array_key_exists('proof-of-work', $dDifficulty))
      $dDifficulty = $dDifficulty['proof-of-work'];
    $memcache->set('dDifficulty', $dDifficulty);
  }
} else {
  $iDifficulty = 1;
}

// Output JSON format
echo json_encode(array('getdifficulty' => $dDifficulty));

// Supress master template
$supress_master = 1;
?>
