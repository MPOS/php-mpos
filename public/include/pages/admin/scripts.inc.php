<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// Check user to ensure they are admin
if (!$user->isAuthenticated() || !$user->isAdmin($_SESSION['USERDATA']['id'])) {
  header("HTTP/1.1 404 Page not found");
  die("404 Page not found");
}

$script_settings_file = "/mpos/cronjobs/settings.json";

if (@$_REQUEST['do'] == 'payout') {
  $coin_name = $_REQUEST['data']['coin'];

  $output = `/mpos/cronjobs/run-convertible.sh -d $coin_name -f`;
} else if (@$_REQUEST['do'] == 'change_mode') {
  $mode = $_REQUEST['data']['mode'];
  
  if ($mode == 'test') {
    $output_array = array('process' => false, 'payout_mode' => false);
  } else if ($mode == 'payout_mode') {
    $output_array = array('process' => false, 'payout_mode' => true);
  } else if ($mode == 'process') {
    $output_array = array('process' => true, 'payout_mode' => false);
  }

  file_put_contents($script_settings_file, json_encode($output_array));
} else if (@$_REQUEST['do'] == 'prices') {
  $output = `/mpos/cronjobs/update_prices.sh`;
} else if (@$_REQUEST['do'] == 'multiport') {
  $coin_names = explode(",", rtrim($_REQUEST['data']['coin']));
  $cmdline = "/mpos/cronjobs/change_coins.sh " . $coin_names[0] . " " . $coin_names[1] . " " . $coin_names[2];
  $output = `sudo $cmdline 2>&1`;
}

$script_settings = json_decode(file_get_contents($script_settings_file));

if ($script_settings->process) {
  $smarty->assign("SCRIPT_MODE", "Payout");
} else if ($script_settings->payout_mode) {
  $smarty->assign("SCRIPT_MODE", "Send To Exchange");
} else {
  $smarty->assign("SCRIPT_MODE", "Test");
}

if (isset($output)) {
  $smarty->assign("OUTPUT", nl2br($output));
}

$smarty->assign("CONTENT", "default.tpl");
?>
