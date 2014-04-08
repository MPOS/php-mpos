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
   * Calculate the PPS value for this coin
   * WARNING: Get this wrong and you will over- or underpay your miners!
   **/
  public function calcPPSValue($pps_reward, $dDifficulty) {
    return ($pps_reward / (pow(2, $this->target_bits) * $dDifficulty));
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
    return (int)round(pow(2, (32 - $this->target_bits)) * $dDifficulty, 0);
  }

  /**
   * Calculate our networks expected time per block
   **/
  public function calcNetworkExpectedTimePerBlock($dDifficulty, $dNetworkHashrate) {
    if ($dNetworkHashrate > 0) {
      return pow(2, 32) * $dDifficulty / $dNetworkHashrate;
    } else {
      return 0;
    }
  }
  /**
   * Calculate next expected difficulty based on current difficulty
   **/
  public function calcExpectedNextDifficulty($dDifficulty, $dNetworkHashrate) {
    $iExpectedTimePerBlock = $this->calcNetworkExpectedTimePerBlock($dDifficulty, $dNetworkHashrate);
    if (!empty($iExpectedTimePerBlock) && $iExpectedTimePerBlock > 0) {
      return round($dDifficulty * $this->config['cointarget'] / $iExpectedTimePerBlock, 8);
    } else {
      return 0;
    }
  }
}

?>
