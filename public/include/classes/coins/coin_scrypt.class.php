<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

/**
 * We extend our CoinBase class
 * No need to change anything, base class supports
 * scrypt and sha256d
 **/
class Coin extends CoinBase {
  protected $target_bits = 16;
}

?>
