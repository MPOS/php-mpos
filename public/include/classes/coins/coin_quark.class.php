<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

/**
 * Extend Coin Base for Quark.
 * Quark
 * Calculated using the FAQ and the difference between stratum and mpos
 * This will give you Stratum Difficulty of 4
 * With a target bit of 18 in MPOS
 * Not finished - Possibly not right.
 **/
class Coin extends CoinBase {
  protected $target_bits = 18;
}


/**
   * Calculate our hashrate based on shares inserted to DB
   * We use diff1 share values, not a baseline one
   **/
  public function calcHashrate($shares, $interval) {
    return $shares * pow(2, $this->target_bits) / $interval / 1000 / 4.5;
  }

  /**
   * Calculate estimated shares of this coin, this is using baseline
   * according to our configuration difficulty
   **/
  public function calcEstaimtedShares($dDifficulty) {
    return (int)round((pow(2, (32 - $this->target_bits)) * $dDifficulty) / pow(2, ($this->config['difficulty'] - 16)) / 4.5);
  }
  
   /**
   * Calculate our networks expected time per block
   **/
  public function calcNetworkExpectedTimePerBlock($dDifficulty, $dNetworkHashrate) {
    return pow(2, 32) * $dDifficulty / $dNetworkHashrate / 4.5;
  }
  
  /**
   * Calculate next expected difficulty based on current difficulty
   **/
  public function calcExpectedNextDifficulty($dDifficulty, $dNetworkHashrate) {
    return round(($dDifficulty * $this->config['cointarget'] / $this->calcNetworkExpectedTimePerBlock($dDifficulty, $dNetworkHashrate), 8) / 4.5);
  }
}
