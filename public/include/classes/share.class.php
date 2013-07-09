<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
  die('Hacking attempt');

class Share {
  private $sError = '';
  private $table = 'shares';
  private $tableArchive = 'shares_archive';
  private $oUpstream;
  private $iLastUpstreamId;
  // This defines each share
  public $rem_host, $username, $our_result, $upstream_result, $reason, $solution, $time;

  public function __construct($debug, $mysqli, $salt) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->debug->append("Instantiated Share class", 2);
  }

  // get and set methods
  private function setErrorMessage($msg) {
    $this->sError = $msg;
  }
  public function getError() {
    return $this->sError;
  }

  /**
   * Fetch archive tables name for this class
   * @param none
   * @return data string Table name
   **/
  public function getArchiveTableName() {
    return $this->tableArchive;
  }
  /**
   * Fetch normal table name for this class
   * @param none
   * @return data string Table name
   **/
  public function getTableName() {
    return $this->table;
  }

  /**
   * Get last inserted Share ID from Database
   * Used for PPS calculations without moving to archive
   **/
  public function getLastInsertedShareId() {
    $stmt = $this->mysqli->prepare("
      SELECT MAX(id) AS id FROM $this->table
      ");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->id;
    // Catchall
    $this->setErrorMessage('Failed to fetch last inserted share ID');
    return false;
  }

  /**
   * Get all valid shares for this round
   * @param previous_upstream int Previous found share accepted by upstream to limit results
   * @param current_upstream int Current upstream accepted share
   * @return data int Total amount of counted shares
   **/
  public function getRoundShares($previous_upstream=0, $current_upstream) {
    $stmt = $this->mysqli->prepare("SELECT
      count(id) as total
      FROM $this->table
      WHERE our_result = 'Y'
      AND id BETWEEN ? AND ?
      ");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('ii', $previous_upstream, $current_upstream);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_object()->total;
    }
    return false;
  }

  /**
   * Fetch all shares grouped by accounts to count share per account
   * @param previous_upstream int Previous found share accepted by upstream to limit results
   * @param current_upstream int Current upstream accepted share
   * @return data array username, valid and invalid shares from account
   **/
  public function getSharesForAccounts($previous_upstream=0, $current_upstream) {
    $stmt = $this->mysqli->prepare("SELECT
      a.id,
      validT.account AS username,
      sum(validT.valid) as valid,
      IFNULL(sum(invalidT.invalid),0) as invalid
      FROM
      (
        SELECT DISTINCT
        SUBSTRING_INDEX( `username` , '.', 1 ) as account,
          COUNT(id) AS valid
          FROM $this->table
          WHERE id BETWEEN ? AND ?
          AND our_result = 'Y'
          GROUP BY account
        ) validT
        LEFT JOIN
        (
          SELECT DISTINCT
          SUBSTRING_INDEX( `username` , '.', 1 ) as account,
            COUNT(id) AS invalid
            FROM $this->table
            WHERE id BETWEEN ? AND ?
            AND our_result = 'N'
            GROUP BY account
          ) invalidT
          ON validT.account = invalidT.account
          INNER JOIN accounts a ON a.username = validT.account
          GROUP BY a.username DESC");
    if ($this->checkStmt($stmt)) {
      $stmt->bind_param('iiii', $previous_upstream, $current_upstream, $previous_upstream, $current_upstream);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      return $result->fetch_all(MYSQLI_ASSOC);
    }
    return false;
  }

  /**
   * Move accounted shares to archive table, this step is optional
   * @param previous_upstream int Previous found share accepted by upstream to limit results
   * @param current_upstream int Current upstream accepted share
   * @param block_id int Block ID to assign shares to a specific block
   * @return bool
   **/
  public function moveArchive($current_upstream, $block_id, $previous_upstream=0) {
    $archive_stmt = $this->mysqli->prepare("INSERT INTO $this->tableArchive (share_id, username, our_result, upstream_result, block_id, time)
      SELECT id, username, our_result, upstream_result, ?, time
      FROM $this->table
      WHERE id BETWEEN ? AND ?");
    if ($this->checkStmt($archive_stmt) && $archive_stmt->bind_param('iii', $block_id, $previous_upstream, $current_upstream) && $archive_stmt->execute()) {
      $archive_stmt->close();
      return true;
    }
    // Catchall
    return false;
  }

  public function deleteAccountedShares($current_upstream, $previous_upstream=0) {
    $stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE id BETWEEN ? AND ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $previous_upstream, $current_upstream) && $stmt->execute())
      return true;
    // Catchall
    return false;
  }
  /**
   * Set/get last found share accepted by upstream: id and accounts
   **/
  public function setLastUpstreamId() {
    $this->iLastUpstreamId = @$this->oUpstream->id ? $this->oUpstream->id : 0;
  }
  public function getLastUpstreamId() {
    return @$this->iLastUpstreamId ? @$this->iLastUpstreamId : 0;
  }
  public function getUpstreamFinder() {
    return @$this->oUpstream->account;
  }
  public function getUpstreamId() {
    return @$this->oUpstream->id;
  }
  /**
   * Find upstream accepted share that should be valid for a specific block
   * Assumptions:
   *  * Shares are matching blocks in ASC order
   *  * We can skip all upstream shares of previously found ones used in a block
   * @param last int Skips all shares up to last to find new share
   * @return bool
   **/
  public function setUpstream($aBlock, $last=0) {
    // Many use stratum, so we create our stratum check first
    $version = pack("I*", sprintf('%08d', $aBlock['version']));
    $previousblockhash = pack("H*", swapEndian($aBlock['previousblockhash']));
    $merkleroot = pack("H*", swapEndian($aBlock['merkleroot']) );
    $time = pack("I*", $aBlock['time']);
    $bits = pack("H*", swapEndian($aBlock['bits']));
    $nonce = pack("I*", $aBlock['nonce']);
    $header_bin = $version .  $previousblockhash . $merkleroot . $time .  $bits . $nonce;
    $header_hex = implode(unpack("H*", $header_bin));
    $scrypt_hash = swapEndian(bin2hex(Scrypt::calc($header_bin, $header_bin, 1024, 1, 1, 32)));

    // Fallback to pushpoold solution type
    $ppheader = sprintf('%08d', $aBlock['version']) . word_reverse($aBlock['previousblockhash']) . word_reverse($aBlock['merkleroot']) . dechex($aBlock['time']) . $aBlock['bits'] . dechex($aBlock['nonce']);

    $stmt = $this->mysqli->prepare("SELECT SUBSTRING_INDEX( `username` , '.', 1 ) AS account, id FROM $this->table WHERE solution = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $scrypt_hash) && $stmt->execute() && $result = $stmt->get_result()) {
      $this->oUpstream = $result->fetch_object();
      $this->share_type = 'startum_solution';
      if (!empty($this->oUpstream->account) && is_int($this->oUpstream->id))
        return true;
    }

    // Failed to fetch via startum solution, try pushpoold
    $stmt = $this->mysqli->prepare("SELECT SUBSTRING_INDEX( `username` , '.', 1 ) AS account, id FROM $this->table WHERE solution LIKE CONCAT(?, '%') LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $ppheader) && $stmt->execute() && $result = $stmt->get_result()) {
      $this->oUpstream = $result->fetch_object();
      $this->share_type = 'pp_solution';
      if (!empty($this->oUpstream->account) && is_int($this->oUpstream->id))
        return true;
    }

    // Still no match, try upstream result with timerange
    $stmt = $this->mysqli->prepare("
      SELECT
      SUBSTRING_INDEX( `username` , '.', 1 ) AS account, id
      FROM $this->table
      WHERE upstream_result = 'Y'
      AND id > ?
      AND UNIX_TIMESTAMP(time) >= ?
      AND UNIX_TIMESTAMP(time) <= ? + 60
      ORDER BY id ASC LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('iii', $last, $aBlock['time'], $Block['time']) && $stmt->execute() && $result = $stmt->get_result()) {
      $this->oUpstream = $result->fetch_object();
      $this->share_type = 'upstream_share';
      if (!empty($this->oUpstream->account) && is_int($this->oUpstream->id))
        return true;
    }

    // We failed again, now we take ANY result matching the timestamp
    $stmt = $this->mysqli->prepare("
      SELECT
      SUBSTRING_INDEX( `username` , '.', 1 ) AS account, id
      FROM $this->table
      WHERE our_result = 'Y'
      AND id > ?
      AND UNIX_TIMESTAMP(time) >= ?
      ORDER BY id ASC LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $last, $aBlock['time']) && $stmt->execute() && $result = $stmt->get_result()) {
      $this->oUpstream = $result->fetch_object();
      $this->share_type = 'any_share';
      if (!empty($this->oUpstream->account) && is_int($this->oUpstream->id))
        return true;
    }
    // Catchall
    return false;
  }

  /**
   * Helper function
   **/
  private function checkStmt($bState) {
    if ($bState ===! true) {
      $this->debug->append("Failed to prepare statement: " . $this->mysqli->error);
      $this->setErrorMessage('Internal application Error');
      return false;
    }
    return true;
  }
}

$share = new Share($debug, $mysqli, SALT);
