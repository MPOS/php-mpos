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
  public $rem_host, $username, $our_result, $upstream_result, $reason, $solution, $time, $difficulty;

  public function __construct($debug, $mysqli, $user, $block, $config) {
    $this->debug = $debug;
    $this->mysqli = $mysqli;
    $this->user = $user;
    $this->config = $config;
    $this->block = $block;
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
      ROUND(IFNULL(SUM(IF(difficulty=0, POW(2, (" . $this->config['difficulty'] . " - 16)), difficulty)), 0) / POW(2, (" . $this->config['difficulty'] . " - 16)), 8) AS total
      FROM $this->table
      WHERE our_result = 'Y'
      AND id > ? AND id <= ?
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
   * @param limit int Limit to this amount of shares for PPLNS
   * @return data array username, valid and invalid shares from account
   **/
  public function getSharesForAccounts($previous_upstream=0, $current_upstream) {
    $stmt = $this->mysqli->prepare("
      SELECT
        a.id,
        SUBSTRING_INDEX( s.username , '.', 1 ) as username,
        a.no_fees AS no_fees,
        ROUND(IFNULL(SUM(IF(our_result='Y', IF(s.difficulty=0, POW(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty), 0)), 0) / POW(2, (" . $this->config['difficulty'] . " - 16)), 8) AS valid,
        ROUND(IFNULL(SUM(IF(our_result='N', IF(s.difficulty=0, POW(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty), 0)), 0) / POW(2, (" . $this->config['difficulty'] . " - 16)), 8) AS invalid
      FROM $this->table AS s
      LEFT JOIN " . $this->user->getTableName() . " AS a
      ON a.username = SUBSTRING_INDEX( s.username , '.', 1 )
      WHERE s.id > ? AND s.id <= ?
      GROUP BY username DESC
      ");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $previous_upstream, $current_upstream) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return false;
  }

  /**
   * Fetch the highest available share ID
   **/
  function getMaxShareId() {
    $stmt = $this->mysqli->prepare("SELECT MAX(id) AS id FROM $this->table");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->id;
    return false;
  }

  /**
   * Fetch the highest available share ID from archive
   **/
  function getMaxArchiveShareId() {
    $stmt = $this->mysqli->prepare("
      SELECT MAX(share_id) AS share_id FROM $this->tableArchive
      ");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->share_id;
    return false;
  }

  /**
   * We need a certain amount of valid archived shares
   * param left int Left/lowest share ID
   * param right int Right/highest share ID
   * return array data Returns an array with usernames as keys for easy access
   **/
  function getArchiveShares($iCount) {
    $iMinId = $this->getMinArchiveShareId($iCount);
    $iMaxId = $this->getMaxArchiveShareId();
    $stmt = $this->mysqli->prepare("
      SELECT
        a.id,
        SUBSTRING_INDEX( s.username , '.', 1 ) as account,
        a.no_fees AS no_fees,
        ROUND(IFNULL(SUM(IF(our_result='Y', IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty), 0)), 0) / POW(2, (" . $this->config['difficulty'] . " - 16)), 8) AS valid,
        ROUND(IFNULL(SUM(IF(our_result='N', IF(s.difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), s.difficulty), 0)), 0) / POW(2, (" . $this->config['difficulty'] . " - 16)), 8) AS invalid
      FROM $this->tableArchive AS s
      LEFT JOIN " . $this->user->getTableName() . " AS a
      ON a.username = SUBSTRING_INDEX( s.username , '.', 1 )
      WHERE s.share_id > ? AND s.share_id <= ?
      GROUP BY account DESC");
    if ($this->checkStmt($stmt) && $stmt->bind_param("ii", $iMinId, $iMaxId) && $stmt->execute() && $result = $stmt->get_result()) {
      $aData = NULL;
      while ($row = $result->fetch_assoc()) {
        $aData[$row['account']] = $row;
      }
      if (is_array($aData)) return $aData;
    }
    return false;
  }

  /**
   * We keep shares only up to a certain point
   * This can be configured by the user.
   * @return return bool true or false
   **/
  public function purgeArchive() {
    if ($this->config['payout_system'] == 'pplns') {
      // Fetch our last block so we can go back configured rounds
      $aLastBlock = $this->block->getLast();
      // Fetch the block we need to find the share_id
      $aBlock = $this->block->getBlock($aLastBlock['height'] - $this->config['archive']['maxrounds']);
      // Now that we know our block, remove those shares
      $stmt = $this->mysqli->prepare("DELETE FROM $this->tableArchive WHERE block_id < ? AND time < DATE_SUB(now(), INTERVAL ? MINUTE)");
      if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $aBlock['id'], $this->config['archive']['maxage']) && $stmt->execute())
        return true;
    } else {
      // We are not running pplns, so we just need to keep shares of the past <interval> minutes
      $stmt = $this->mysqli->prepare("DELETE FROM $this->tableArchive WHERE time < DATE_SUB(now(), INTERVAL ? MINUTE)");
      if ($this->checkStmt($stmt) && $stmt->bind_param('i', $this->config['archive']['maxage']) && $stmt->execute())
      return true;
    }
    // Catchall
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
    $archive_stmt = $this->mysqli->prepare("
      INSERT INTO $this->tableArchive (share_id, username, our_result, upstream_result, block_id, time, difficulty)
        SELECT id, username, our_result, upstream_result, ?, time, IF(difficulty=0, pow(2, (" . $this->config['difficulty'] . " - 16)), difficulty) AS difficulty
        FROM $this->table
        WHERE id > ? AND id <= ?");
    if ($this->checkStmt($archive_stmt) && $archive_stmt->bind_param('iii', $block_id, $previous_upstream, $current_upstream) && $archive_stmt->execute()) {
      $archive_stmt->close();
      return true;
    }
    // Catchall
    return false;
  }

  public function deleteAccountedShares($current_upstream, $previous_upstream=0) {
    $stmt = $this->mysqli->prepare("DELETE FROM $this->table WHERE id > ? AND id <= ?");
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

    // Stratum supported blockhash solution entry
    $stmt = $this->mysqli->prepare("SELECT SUBSTRING_INDEX( `username` , '.', 1 ) AS account, id FROM $this->table WHERE solution = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $aBlock['hash']) && $stmt->execute() && $result = $stmt->get_result()) {
      $this->oUpstream = $result->fetch_object();
      $this->share_type = 'startum_blockhash';
      if (!empty($this->oUpstream->account) && is_int($this->oUpstream->id))
        return true;
    }

    // Stratum scrypt hash check
    $scrypt_hash = swapEndian(bin2hex(Scrypt::calc($header_bin, $header_bin, 1024, 1, 1, 32)));
    $stmt = $this->mysqli->prepare("SELECT SUBSTRING_INDEX( `username` , '.', 1 ) AS account, id FROM $this->table WHERE solution = ? LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('s', $scrypt_hash) && $stmt->execute() && $result = $stmt->get_result()) {
      $this->oUpstream = $result->fetch_object();
      $this->share_type = 'startum_solution';
      if (!empty($this->oUpstream->account) && is_int($this->oUpstream->id))
        return true;
    }

    // Failed to fetch via startum solution, try pushpoold
    // Fallback to pushpoold solution type
    $ppheader = sprintf('%08d', $aBlock['version']) . word_reverse($aBlock['previousblockhash']) . word_reverse($aBlock['merkleroot']) . dechex($aBlock['time']) . $aBlock['bits'] . dechex($aBlock['nonce']);
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
      AND UNIX_TIMESTAMP(time) <= ( ? + 60 )
      ORDER BY id ASC LIMIT 1");
    if ($this->checkStmt($stmt) && $stmt->bind_param('iii', $last, $aBlock['time'], $aBlock['time']) && $stmt->execute() && $result = $stmt->get_result()) {
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
   * Fetch the lowest needed share ID from shares
   **/
  function getMinimumShareId($iCount, $current_upstream) {
    // We don't use baseline here to be more accurate
    $iCount = $iCount * pow(2, ($this->config['difficulty'] - 16));
    $stmt = $this->mysqli->prepare("
      SELECT MIN(b.id) AS id FROM
      (
        SELECT id, @total := @total + IF(difficulty=0, POW(2, (" . $this->config['difficulty'] . " - 16)), difficulty) AS total
        FROM $this->table, (SELECT @total := 0) AS a
        WHERE our_result = 'Y'
        AND id <= ? AND @total < ?
        ORDER BY id DESC
      ) AS b
      WHERE total <= ?
      ");
    if ($this->checkStmt($stmt) && $stmt->bind_param('iii', $current_upstream, $iCount, $iCount) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->id;
    return false;
  }

  /**
   * Fetch the lowest needed share ID from archive
   **/
  function getMinArchiveShareId($iCount) {
    $stmt = $this->mysqli->prepare("
      SELECT MIN(b.share_id) AS share_id FROM
      (
        SELECT share_id, @total := @total + (IF(difficulty=0, POW(2, (" . $this->config['difficulty'] . " - 16)), difficulty) / POW(2, (" . $this->config['difficulty'] . " - 16))) AS total
        FROM $this->tableArchive, (SELECT @total := 0) AS a
        WHERE our_result = 'Y'
        AND @total < ?
        ORDER BY share_id DESC
      ) AS b
      WHERE total <= ?
      ");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $iCount, $iCount) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_object()->share_id;
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

$share = new Share($debug, $mysqli, $user, $block, $config);
