<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// check if fees are 0 and ap/mp tx fees are also set to 0 -> issue #2424
if ($config['fees'] == 0 && ($config['txfee_auto'] == 0 || $config['txfee_manual'] == 0)) {
  $newerror = array();
  $newerror['name'] = "Fees and TX Fees 0";
  $newerror['level'] = 2;
  $newerror['extdesc'] = "This is an issue that can only occur with both your fees set to 0 and auto or manual tx fees set to 0 as well. It's best to avoid it if possible though, as it can prevent payouts; set the txfee to a small amount to avoid this.";
  $newerror['description'] = "Having your pool fees set to 0 and tx fees also set to 0 can cause a problem where the wallet cannot payout, consider setting the txfee to a very low amount, ie. 0.0001 to avoid this.";
  $newerror['configvalue'] = "fees";
  $newerror['helplink'] = "https://github.com/MPOS/php-mpos/issues/2424";
  $error[] = $newerror;
  $newerror = null;
}
