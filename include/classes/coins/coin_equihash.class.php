<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class Coin extends CoinBase {
  protected $target_bits = 24;
}
