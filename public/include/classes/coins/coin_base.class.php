<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;


/**
 * Our coin base class that we extend our other coins from
 *
 * We are implementing all basic coin methods into this class but it
 * must be extended for customized coins.
 **/
class CoinBase extends Base {
  // Our coins target bits
  protected $target_bits = NULL;

  /**
   * Read our target bits
   **/
  public function getTargetBits() {
    return $this->target_bits;
  }

  /**
   * Calculate our hashrate based on shares inserted to DB
   * We use diff1 share values, not a baseline one
   **/
  public function calcHashrate($shares, $interval) {
    return $shares * pow(2, $this->target_bits) / $interval / 1000;
  }

  /**
   * Calculate estimated shares of this coin, this is using baseline
   * according to our configuration difficulty
   **/
  public function calcEstaimtedShares($dDifficulty) {
    return (int)round((pow(2, (32 - $this->target_bits)) * $dDifficulty) / pow(2, ($this->config['difficulty'] - 16)));
  }

  /**
   * Calculate our networks expected time per block
   **/
  public function calcNetworkExpectedTimePerBlock($dDifficulty, $dNetworkHashrate) {
    return pow(2, 32) * $dDifficulty / $dNetworkHashrate;
  }
  /**
   * Calculate next expected difficulty based on current difficulty
   **/
  public function calcExpectedNextDifficulty($dDifficulty, $dNetworkHashrate) {
    return round($dDifficulty * $this->config['cointarget'] / $this->calcNetworkExpectedTimePerBlock($dDifficulty, $dNetworkHashrate), 8);
  }
}

?>
