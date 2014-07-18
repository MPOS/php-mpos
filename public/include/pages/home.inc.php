<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Include markdown library
use \Michelf\Markdown;


$smarty->assign('coin_name', $currency);

if (in_array($currency, array('WC', 'SUM', 'BNS', 'UVC', 'HYPER'))) {
  $pool_status = json_decode(file_get_contents("http://chunkypools.com/api/pool/status"));

  $smarty->assign('coin_hash_rate', $pool_status->hash_rate);
  $smarty->assign('coin_workers', $pool_status->workers);

  $multiport_coins_file = "/mpos/public/multiport_coins.txt";
  $multiport_coins = explode(",", rtrim(file_get_contents($multiport_coins_file)));

  $smarty->assign("scrypt_coin", $multiport_coins[0]);
  $smarty->assign("x11_coin", $multiport_coins[1]);
  $smarty->assign("sha256_coin", $multiport_coins[2]);
  $smarty->assign("coin_dictionary", array("rdd" => "REDDCOIN", "lgc" => "LOGICOIN", "karm" => "KARMACOIN", "trc" => "TERRACOIN", "pot" => "POTCOIN"));
}


if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  // Fetch active news to display
  $aNews = $news->getAllActive();
  if (is_array($aNews)) {
    foreach ($aNews as $key => $aData) {
      // Transform Markdown content to HTML
      $aNews[$key]['content'] = Markdown::defaultTransform($aData['content']);
    }
  }

  if ($bitcoin->can_connect() === true) {
    $smarty->assign("DIFFICULTY", $bitcoin->getdifficulty());
  }

  $smarty->assign("HIDEAUTHOR", $setting->getValue('acl_hide_news_author'));
  $smarty->assign("NEWS", $aNews);
} else {
  $debug->append('Using cached page', 3);
}
// Load news entries for Desktop site and unauthenticated users
$smarty->assign("CONTENT", "default.tpl");
?>
