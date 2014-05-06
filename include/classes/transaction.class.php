<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

class Transaction extends Base {
  protected $table = 'transactions';
  public $num_rows = 0, $insert_id = 0;

  /**
   * Add a new transaction to our class table
   * We also store the inserted ID in case the user needs it
   * @param account_id int Account ID to book transaction for
   * @param amount float Coin amount
   * @param type string Transaction type [Credit, Debit_AP, Debit_MP, Fee, Donation, Orphan_Credit, Orphan_Fee, Orphan_Donation]
   * @param block_id int Block ID to link transaction to [optional]
   * @param coin_address string Coin address for this transaction [optional]
   * @return bool
   **/
  public function addTransaction($account_id, $amount, $type='Credit', $block_id=NULL, $coin_address=NULL, $txid=NULL) {
    $stmt = $this->mysqli->prepare("INSERT INTO $this->table (account_id, amount, block_id, type, coin_address, txid) VALUES (?, ?, ?, ?, ?, ?)");
    if ($this->checkStmt($stmt) && $stmt->bind_param("idisss", $account_id, $amount, $block_id, $type, $coin_address, $txid) && $stmt->execute()) {
      $this->insert_id = $stmt->insert_id;
      return true;
    }
    return $this->sqlError();
  }

  /**
   * Update a transaction with a RPC transaction ID
   * @param id integer Transaction ID
   * @param txid string RPC Transaction Identifier
   * @return bool true or false
   **/
  public function setRPCTxId($transaction_id, $rpc_txid=NULL) {
    $stmt = $this->mysqli->prepare("UPDATE $this->table SET txid = ? WHERE id = ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('si', $rpc_txid, $transaction_id) && $stmt->execute())
      return true;
    return $this->sqlError();
  }

  /*
   * Mark transactions of a user as archived
   * @param account_id int Account ID
   * @param txid int Transaction ID to start from
   * @param bool boolean True or False
   **/
  public function setArchived($account_id, $txid) {
    // Update all paid out transactions as archived
    $stmt = $this->mysqli->prepare("
      UPDATE $this->table AS t
      LEFT JOIN " . $this->block->getTableName() . " AS b
      ON b.id = t.block_id
      SET t.archived = 1
      WHERE t.archived = 0
      AND (
           ( t.account_id = ? AND t.id <= ? AND b.confirmations >= ? )
        OR ( t.account_id = ? AND t.id <= ? AND b.confirmations = -1 )
        OR ( t.account_id = ? AND t.id <= ? AND t.type IN ( 'Credit_PPS', 'Donation_PPS', 'Fee_PPS', 'TXFee', 'Debit_MP', 'Debit_AP' ) )
      )");
     if ($this->checkStmt($stmt) && $stmt->bind_param('iiiiiii', $account_id, $txid, $this->config['confirmations'], $account_id, $txid, $account_id, $txid) && $stmt->execute())
      return true;
    return $this->sqlError();
  }

  /**
   * Fetch a transaction summary by type with total amounts
   * @param account_id int Account ID, NULL for all
   * @return data array type and total
   **/
  public function getTransactionSummary($account_id=NULL) {
    if ($data = $this->memcache->get(__FUNCTION__ . $account_id)) return $data;
    $sql = "
      SELECT
        SUM(t.amount) AS total, t.type AS type
      FROM $this->table AS t
      LEFT OUTER JOIN " . $this->block->getTableName() . " AS b
      ON b.id = t.block_id
      WHERE ( b.confirmations > 0 OR b.id IS NULL )";
    if (!empty($account_id)) {
      $sql .= " AND t.account_id = ? ";
      $this->addParam('i', $account_id);
    }
    $sql .= " GROUP BY t.type";
    $stmt = $this->mysqli->prepare($sql);
    if (!empty($account_id)) {
      if (!($this->checkStmt($stmt) && call_user_func_array( array($stmt, 'bind_param'), $this->getParam()) && $stmt->execute()))
        return false;
      $result = $stmt->get_result();
    } else {
      if (!($this->checkStmt($stmt) && $stmt->execute()))
        return false;
      $result = $stmt->get_result();
    }
    if ($result) {
      $aData = NULL;
      while ($row = $result->fetch_assoc()) {
        $aData[$row['type']] = $row['total'];
      }
      // Cache data for a while, query takes long on many rows
      return $this->memcache->setCache(__FUNCTION__ . $account_id, $aData, 60);
    }
    return $this->sqlError();
  }


  /**
   * Fetch a transaction summary by user with total amounts
   * @param account_id int Account ID, NULL for all
   * @return data array type and total
   **/
  public function getTransactionTypebyTime($account_id=NULL) {
    $this->debug->append("STA " . __METHOD__, 4);
    if ($data = $this->memcache->get(__FUNCTION__)) return $data;
    $stmt = $this->mysqli->prepare("
      SELECT
        IFNULL(SUM(IF(t.type = 'Credit' AND timestamp >= DATE_SUB(now(), INTERVAL 3600 SECOND), t.amount, 0)), 0) AS 1HourCredit,
        IFNULL(SUM(IF(t.type = 'Bonus' AND timestamp >= DATE_SUB(now(), INTERVAL 3600 SECOND), t.amount, 0)), 0) AS 1HourBonus,
        IFNULL(SUM(IF(t.type = 'Debit_MP' AND timestamp >= DATE_SUB(now(), INTERVAL 3600 SECOND), t.amount, 0)), 0) AS 1HourDebitMP,
        IFNULL(SUM(IF(t.type = 'Debit_AP' AND timestamp >= DATE_SUB(now(), INTERVAL 3600 SECOND), t.amount, 0)), 0) AS 1HourDebitAP,
        IFNULL(SUM(IF(t.type = 'TXFee' AND timestamp >= DATE_SUB(now(), INTERVAL 3600 SECOND), t.amount, 0)), 0) AS 1HourTXFee,
        IFNULL(SUM(IF(t.type = 'Fee' AND timestamp >= DATE_SUB(now(), INTERVAL 3600 SECOND), t.amount, 0)), 0) AS 1HourFee,
        IFNULL(SUM(IF(t.type = 'Donation' AND timestamp >= DATE_SUB(now(), INTERVAL 3600 SECOND), t.amount, 0)), 0) AS 1HourDonation,

        IFNULL(SUM(IF(t.type = 'Credit' AND timestamp >= DATE_SUB(now(), INTERVAL 86400 SECOND), t.amount, 0)), 0) AS 24HourCredit,
        IFNULL(SUM(IF(t.type = 'Bonus' AND timestamp >= DATE_SUB(now(), INTERVAL 86400 SECOND), t.amount, 0)), 0) AS 24HourBonus,
        IFNULL(SUM(IF(t.type = 'Debit_MP' AND timestamp >= DATE_SUB(now(), INTERVAL 86400 SECOND), t.amount, 0)), 0) AS 24HourDebitMP,
        IFNULL(SUM(IF(t.type = 'Debit_AP' AND timestamp >= DATE_SUB(now(), INTERVAL 86400 SECOND), t.amount, 0)), 0) AS 24HourDebitAP,
        IFNULL(SUM(IF(t.type = 'TXFee' AND timestamp >= DATE_SUB(now(), INTERVAL 86400 SECOND), t.amount, 0)), 0) AS 24HourTXFee,
        IFNULL(SUM(IF(t.type = 'Fee' AND timestamp >= DATE_SUB(now(), INTERVAL 86400 SECOND), t.amount, 0)), 0) AS 24HourFee,
        IFNULL(SUM(IF(t.type = 'Donation' AND timestamp >= DATE_SUB(now(), INTERVAL 86400 SECOND), t.amount, 0)), 0) AS 24HourDonation,

        IFNULL(SUM(IF(t.type = 'Credit' AND timestamp >= DATE_SUB(now(), INTERVAL 604800 SECOND), t.amount, 0)), 0) AS 1WeekCredit,
        IFNULL(SUM(IF(t.type = 'Bonus' AND timestamp >= DATE_SUB(now(), INTERVAL 604800 SECOND), t.amount, 0)), 0) AS 1WeekBonus,
        IFNULL(SUM(IF(t.type = 'Debit_MP' AND timestamp >= DATE_SUB(now(), INTERVAL 604800 SECOND), t.amount, 0)), 0) AS 1WeekDebitMP,
        IFNULL(SUM(IF(t.type = 'Debit_AP' AND timestamp >= DATE_SUB(now(), INTERVAL 604800 SECOND), t.amount, 0)), 0) AS 1WeekDebitAP,
        IFNULL(SUM(IF(t.type = 'TXFee' AND timestamp >= DATE_SUB(now(), INTERVAL 604800 SECOND), t.amount, 0)), 0) AS 1WeekTXFee,
        IFNULL(SUM(IF(t.type = 'Fee' AND timestamp >= DATE_SUB(now(), INTERVAL 604800 SECOND), t.amount, 0)), 0) AS 1WeekFee,
        IFNULL(SUM(IF(t.type = 'Donation' AND timestamp >= DATE_SUB(now(), INTERVAL 604800 SECOND), t.amount, 0)), 0) AS 1WeekDonation,

        IFNULL(SUM(IF(t.type = 'Credit' AND timestamp >= DATE_SUB(now(), INTERVAL 2419200 SECOND), t.amount, 0)), 0) AS 1MonthCredit,
        IFNULL(SUM(IF(t.type = 'Bonus' AND timestamp >= DATE_SUB(now(), INTERVAL 2419200 SECOND), t.amount, 0)), 0) AS 1MonthBonus,
        IFNULL(SUM(IF(t.type = 'Debit_MP' AND timestamp >= DATE_SUB(now(), INTERVAL 2419200 SECOND), t.amount, 0)), 0) AS 1MonthDebitMP,
        IFNULL(SUM(IF(t.type = 'Debit_AP' AND timestamp >= DATE_SUB(now(), INTERVAL 2419200 SECOND), t.amount, 0)), 0) AS 1MonthDebitAP,
        IFNULL(SUM(IF(t.type = 'TXFee' AND timestamp >= DATE_SUB(now(), INTERVAL 2419200 SECOND), t.amount, 0)), 0) AS 1MonthTXFee,
        IFNULL(SUM(IF(t.type = 'Fee' AND timestamp >= DATE_SUB(now(), INTERVAL 2419200 SECOND), t.amount, 0)), 0) AS 1MonthFee,
        IFNULL(SUM(IF(t.type = 'Donation' AND timestamp >= DATE_SUB(now(), INTERVAL 2419200 SECOND), t.amount, 0)), 0) AS 1MonthDonation,

        IFNULL(SUM(IF(t.type = 'Credit' AND timestamp >= DATE_SUB(now(), INTERVAL 31536000 SECOND), t.amount, 0)), 0) AS 1YearCredit,
        IFNULL(SUM(IF(t.type = 'Bonus' AND timestamp >= DATE_SUB(now(), INTERVAL 31536000 SECOND), t.amount, 0)), 0) AS 1YearBonus,
        IFNULL(SUM(IF(t.type = 'Debit_MP' AND timestamp >= DATE_SUB(now(), INTERVAL 31536000 SECOND), t.amount, 0)), 0) AS 1YearDebitMP,
        IFNULL(SUM(IF(t.type = 'Debit_AP' AND timestamp >= DATE_SUB(now(), INTERVAL 31536000 SECOND), t.amount, 0)), 0) AS 1YearDebitAP,
        IFNULL(SUM(IF(t.type = 'TXFee' AND timestamp >= DATE_SUB(now(), INTERVAL 31536000 SECOND), t.amount, 0)), 0) AS 1YearTXFee,
        IFNULL(SUM(IF(t.type = 'Fee' AND timestamp >= DATE_SUB(now(), INTERVAL 31536000 SECOND), t.amount, 0)), 0) AS 1YearFee,
        IFNULL(SUM(IF(t.type = 'Donation' AND timestamp >= DATE_SUB(now(), INTERVAL 31536000 SECOND), t.amount, 0)), 0) AS 1YearDonation
      FROM $this->table AS t
      LEFT OUTER JOIN " . $this->block->getTableName() . " AS b ON b.id = t.block_id
      WHERE
        t.account_id = ? AND (b.confirmations > 0 OR b.id IS NULL)");
    if ($this->checkStmt($stmt) && $stmt->bind_param("i", $account_id) && $stmt->execute() && $result = $stmt->get_result())
    	return $this->memcache->setCache(__FUNCTION__ . $account_id, $result->fetch_assoc(), 60);
    return $this->sqlError();
  }

  /**
   * Get all transactions from start for account_id
   * @param start int Starting point, id of transaction
   * @param filter array Filter to limit transactions
   * @param limit int Only display this many transactions
   * @param account_id int Account ID
   * @return data array Database fields as defined in SELECT
   **/
  public function getTransactions($start=0, $filter=NULL, $limit=30, $account_id=NULL) {
    $this->debug->append("STA " . __METHOD__, 4);
    $sql = "
      SELECT
        t.id AS id,
        a.username as username,
        t.type AS type,
        t.amount AS amount,
        t.coin_address AS coin_address,
        t.timestamp AS timestamp,
        t.txid AS txid,
        b.height AS height,
        b.blockhash AS blockhash,
        b.confirmations AS confirmations
      FROM $this->table AS t
      LEFT JOIN " . $this->block->getTableName() . " AS b ON t.block_id = b.id
      LEFT JOIN " . $this->user->getTableName() . " AS a ON t.account_id = a.id";
    if (!empty($account_id)) {
      $sql .= " WHERE ( t.account_id = ? ) ";
      $this->addParam('i', $account_id);
    }
    if (is_array($filter)) {
      $aFilter = array();
      foreach ($filter as $key => $value) {
        if (!empty($value)) {
          switch ($key) {
          case 'type':
            $aFilter[] = "( t.type = ? )";
            $this->addParam('s', $value);
            break;
          case 'status':
            switch ($value) {
            case 'Confirmed':
              if (empty($filter['type']) || ($filter['type'] != 'Debit_AP' && $filter['type'] != 'Debit_MP' && $filter['type'] != 'TXFee' && $filter['type'] != 'Credit_PPS' && $filter['type'] != 'Fee_PPS' && $filter['type'] != 'Donation_PPS')) {
                $aFilter[] = "( b.confirmations >= " . $this->config['confirmations'] . " OR ISNULL(b.confirmations) )";
              }
                break;
            case 'Unconfirmed':
              $aFilter[] = "( b.confirmations < " . $this->config['confirmations'] . " AND b.confirmations >= 0 )";
                break;
            case 'Orphan':
              $aFilter[] = "( b.confirmations = -1 )";
                break;
            }
            break;
            case 'account':
              $aFilter[] = "( LOWER(a.username) = LOWER(?) )";
              $this->addParam('s', $value);
              break;
            case 'address':
              $aFilter[] = "( t.coin_address = ? )";
              $this->addParam('s', $value);
              break;
          }
        }
      }
      if (!empty($aFilter)) {
      	empty($account_id) ? $sql .= " WHERE " : $sql .= " AND ";
        $sql .= implode(' AND ', $aFilter);
      }
    }
    $sql .= " ORDER BY id DESC LIMIT ?,?";
    // Add some other params to query
    $this->addParam('i', $start);
    $this->addParam('i', $limit);
    $stmt = $this->mysqli->prepare($sql);
    if ($this->checkStmt($stmt) && call_user_func_array( array($stmt, 'bind_param'), $this->getParam()) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError();
  }

  /**
   * Get all different transaction types
   * @return mixed array/bool Return types on succes, false on failure
   **/
  public function getTypes() {
    $stmt = $this->mysqli->prepare("SELECT DISTINCT type FROM $this->table");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result()) {
      $aData = array('' => '');
      while ($row = $result->fetch_assoc()) {
        $aData[$row['type']] = $row['type'];
      }
      return $aData;
    }
    return $this->sqlError();
  }

  /**
   * Get all donation transactions
   * Used on donors page
   * return data array Donors and amounts
   **/
  public function getDonations() {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
        SUM(t.amount) AS donation,
        a.username AS username,
        a.is_anonymous AS is_anonymous,
        ROUND(a.donate_percent, 2) AS donate_percent
      FROM $this->table AS t
      LEFT JOIN " . $this->user->getTableName() . " AS a
      ON t.account_id = a.id
      LEFT JOIN " . $this->block->getTableName() . " AS b
      ON t.block_id = b.id
      WHERE
      (
        ( t.type = 'Donation' AND b.confirmations >= " . $this->config['confirmations'] . " ) OR
        t.type = 'Donation_PPS'
      )
      GROUP BY a.username
      ORDER BY donation DESC
      ");
    if ($this->checkStmt($stmt) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError();
  }

  /**
   * Get total balance for all users locked in wallet
   * This includes any outstanding unconfirmed transactions!
   * @param none
   * @return data double Amount locked for users
   **/
  public function getLockedBalance() {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
        ROUND((
          SUM( IF( ( t.type IN ('Credit','Bonus') AND b.confirmations >= ? ) OR t.type = 'Credit_PPS', t.amount, 0 ) ) -
          SUM( IF( t.type IN ('Debit_MP', 'Debit_AP'), t.amount, 0 ) ) -
          SUM( IF( ( t.type IN ('Donation','Fee') AND b.confirmations >= ? ) OR ( t.type IN ('Donation_PPS', 'Fee_PPS', 'TXFee') ), t.amount, 0 ) )
        ), 8) AS balance
      FROM $this->table AS t
      LEFT JOIN " . $this->block->getTableName() . " AS b
      ON t.block_id = b.id
      WHERE archived = 0");
    if ($this->checkStmt($stmt) && $stmt->bind_param('ii', $this->config['confirmations'], $this->config['confirmations']) && $stmt->execute() && $stmt->bind_result($dBalance) && $stmt->fetch())
      return $dBalance;
    return $this->sqlError();
  }

  /**
   * Get an accounts total balance, ignore archived entries
   * @param account_id int Account ID
   * @return data float Credit - Debit - Fees - Donation
   **/
  public function getBalance($account_id) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
        IFNULL(ROUND((
          SUM( IF( ( t.type IN ('Credit','Bonus') AND b.confirmations >= ? ) OR t.type = 'Credit_PPS', t.amount, 0 ) ) -
          SUM( IF( t.type IN ('Debit_MP', 'Debit_AP'), t.amount, 0 ) ) -
          SUM( IF( ( t.type IN ('Donation','Fee') AND b.confirmations >= ? ) OR ( t.type IN ('Donation_PPS', 'Fee_PPS', 'TXFee') ), t.amount, 0 ) )
        ), 8), 0) AS confirmed,
        IFNULL(ROUND((
          SUM( IF( t.type IN ('Credit','Bonus') AND b.confirmations < ? AND b.confirmations >= 0, t.amount, 0 ) ) -
          SUM( IF( t.type IN ('Donation','Fee') AND b.confirmations < ? AND b.confirmations >= 0, t.amount, 0 ) )
        ), 8), 0) AS unconfirmed,
        IFNULL(ROUND((
          SUM( IF( t.type IN ('Credit','Bonus') AND b.confirmations = -1, t.amount, 0) ) -
          SUM( IF( t.type IN ('Donation','Fee') AND b.confirmations = -1, t.amount, 0) )
        ), 8), 0) AS orphaned
      FROM $this->table AS t
      LEFT JOIN " . $this->block->getTableName() . " AS b
      ON t.block_id = b.id
      WHERE t.account_id = ?
      AND archived = 0
      ");
    if ($this->checkStmt($stmt) && $stmt->bind_param("iiiii", $this->config['confirmations'], $this->config['confirmations'], $this->config['confirmations'], $this->config['confirmations'], $account_id) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_assoc();
    return $this->sqlError();
  }

  /**
   * Get our Auto Payout queue
   * @param none
   * @return data array Account settings and confirmed balances
   **/
  public function getAPQueue($limit=250) {
    $this->debug->append("STA " . __METHOD__, 4);
    $stmt = $this->mysqli->prepare("
      SELECT
        a.id,
        a.username,
        a.ap_threshold,
        a.coin_address,
        IFNULL(
          ROUND(
            (
              SUM( IF( ( t.type IN ('Credit','Bonus') AND b.confirmations >= " . $this->config['confirmations'] . ") OR t.type = 'Credit_PPS', t.amount, 0 ) ) -
              SUM( IF( t.type IN ('Debit_MP', 'Debit_AP'), t.amount, 0 ) ) -
              SUM( IF( ( t.type IN ('Donation','Fee') AND b.confirmations >= " . $this->config['confirmations'] . ") OR ( t.type IN ('Donation_PPS', 'Fee_PPS', 'TXFee') ), t.amount, 0 ) )
            ), 8
          ), 0
        ) AS confirmed
      FROM $this->table AS t
      LEFT JOIN " . $this->block->getTableName() . " AS b
      ON t.block_id = b.id
      LEFT JOIN " . $this->user->getTableName() . " AS a
      ON t.account_id = a.id
      WHERE t.archived = 0 AND a.ap_threshold > 0 AND a.coin_address IS NOT NULL AND a.coin_address != ''
      GROUP BY t.account_id
      HAVING confirmed > a.ap_threshold AND confirmed > " . $this->config['txfee_auto'] . "
      LIMIT ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $limit) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError();
  }


  /**
   * Debit a user account
   * @param account_id int Account ID
   * @param coin_address string Coin Address
   * @param amount float Balance to record
   * @return int Debit transaction ID or false
   **/
  public function createDebitMPRecord($account_id, $coin_address, $amount) {
    return $this->createDebitRecord($account_id, $coin_address, $amount, 'Debit_MP');
  }
  public function createDebitAPRecord($account_id, $coin_address, $amount) {
    return $this->createDebitRecord($account_id, $coin_address, $amount, 'Debit_AP');
  }
  private function createDebitRecord($account_id, $coin_address, $amount, $type) {
    // Calculate and deduct txfee from amount
    $type == 'Debit_MP' ? $txfee = $this->config['txfee_manual'] : $txfee = $this->config['txfee_auto'];
    $amount = $amount - $txfee;
    // Add Debit record
    if (!$this->addTransaction($account_id, $amount, $type, NULL, $coin_address, NULL)) {
      $this->setErrorMessage('Failed to create ' . $type . ' transaction record in database');
      return false;
    }
    // Fetch the inserted record ID so we can return this at the end
    $transaction_id = $this->insert_id;
    // Add TXFee record
    if ($txfee > 0) {
      if (!$this->addTransaction($account_id, $txfee, 'TXFee', NULL, $coin_address)) {
        $this->setErrorMessage('Failed to create TXFee transaction record in database: ' . $this->getError());
        return false;
      }
    }
    // Mark transactions archived
    if (!$this->setArchived($account_id, $this->insert_id)) {
      $this->setErrorMessage('Failed to mark transactions <= #' . $this->insert_id . ' as archived. ERROR: ' . $this->getError());
      return false;
    }
    // Recheck the users balance to make sure it is now 0
    if (!$aBalance = $this->getBalance($account_id)) {
      $this->setErrorMessage('Failed to fetch balance for account ' . $account_id . '. ERROR: ' . $this->getCronError());
      return false;
    }
    if ($aBalance['confirmed'] > 0) {
      $this->setErrorMessage('User has a remaining balance of ' . $aBalance['confirmed'] . ' after a successful payout!');
      return false;
    }
    // Notify user via  mail
    $aMailData['email'] = $this->user->getUserEmailById($account_id);
    $aMailData['subject'] = $type . ' Completed';
    $aMailData['amount'] = $amount;
    if (!$this->notification->sendNotification($account_id, 'payout', $aMailData)) {
      $this->setErrorMessage('Failed to send notification email to users address: ' . $aMailData['email'] . 'ERROR: ' . $this->notification->getCronError());
    }
    return $transaction_id;
  }

  /**
   * Get all new, unprocessed manual payout requests
   * @param none
   * @return data Associative array with DB Fields
   **/
  public function getMPQueue($limit=250) {
    $stmt = $this->mysqli->prepare("
      SELECT
      a.id,
      a.username,
      a.ap_threshold,
      a.coin_address,
      p.id AS payout_id,
      IFNULL(
        ROUND(
          (
            SUM( IF( ( t.type IN ('Credit','Bonus') AND b.confirmations >= " . $this->config['confirmations'] . ") OR t.type = 'Credit_PPS', t.amount, 0 ) ) -
            SUM( IF( t.type IN ('Debit_MP', 'Debit_AP'), t.amount, 0 ) ) -
            SUM( IF( ( t.type IN ('Donation','Fee') AND b.confirmations >= " . $this->config['confirmations'] . ") OR ( t.type IN ('Donation_PPS', 'Fee_PPS', 'TXFee') ), t.amount, 0 ) )
          ), 8
        ), 0
      ) AS confirmed
      FROM " . $this->payout->getTableName() . " AS p
      JOIN " . $this->user->getTableName() . " AS a
      ON p.account_id = a.id
      JOIN " . $this->getTableName() . " AS t
      ON t.account_id = p.account_id
      LEFT JOIN " . $this->block->getTableName() . " AS b
      ON t.block_id = b.id
      WHERE p.completed = 0 AND t.archived = 0 AND a.coin_address IS NOT NULL AND a.coin_address != ''
      GROUP BY t.account_id
      HAVING confirmed > " . $this->config['txfee_manual'] . "
      LIMIT ?");
    if ($this->checkStmt($stmt) && $stmt->bind_param('i', $limit) && $stmt->execute() && $result = $stmt->get_result())
      return $result->fetch_all(MYSQLI_ASSOC);
    return $this->sqlError('E0050');
  }
}

$transaction = new Transaction();
$transaction->setMemcache($memcache);
$transaction->setNotification($notification);
$transaction->setDebug($debug);
$transaction->setMysql($mysqli);
$transaction->setConfig($config);
$transaction->setBlock($block);
$transaction->setUser($user);
$transaction->setPayout($oPayout);
$transaction->setErrorCodes($aErrorCodes);
?>
