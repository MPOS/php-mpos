<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  if ($bitcoin->can_connect() === true){
    $dDifficulty = $bitcoin->getdifficulty();
    $iBlock = $bitcoin->getblockcount();
  } else {
    $dDifficulty = 1;
    $iBlock = 0;
    $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to litecoind RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 'alert alert-danger');
  }
  $smarty->assign("CURRENTBLOCK", $iBlock);
  $smarty->assign("DIFFICULTY", $dDifficulty);
} else {
  $debug->append('Using cached page', 3);
}

$smarty->assign("CONTENT", "default.tpl");
?>
