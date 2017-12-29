<?php
/*
 * This class will run queries on master/slave servers depending on the query itself.
 */
class mysqlims extends mysqli
{
    private $mysqliW;
    private $mysqliR = null;
    private $slave = false;
    public $lastused = null;
    
    /*
     * Pass main and slave connection arrays to the constructor, and strict as true/false
     *
     * @param array $main
     * @param array $slave
     * @param boolean $strict
     *
     * @return void
     */
    public function __construct($main, $slave = false, $strict = false)
    {
        if ($strict) {
            $this->mysqliW = new mysqli_strict($main['host'],
                $main['user'], $main['pass'],
                $main['name'], $main['port']);
            if ($slave && is_array($slave) && isset($slave['enabled']) && $slave['enabled']
                === true) {
                $this->mysqliR = new mysqli_strict($slave['host'],
                    $slave['user'], $slave['pass'],
                    $slave['name'], $slave['port']);
                $this->slave = true;
            }
        } else {
            $this->mysqliW = new mysqli($main['host'],
                $main['user'], $main['pass'],
                $main['name'], $main['port']);
            if ($slave && is_array($slave) && isset($slave['enabled']) && $slave['enabled']
                === true) {
                $this->mysqliR = new mysqli($slave['host'],
                    $slave['user'], $slave['pass'],
                    $slave['name'], $slave['port']);
                $this->slave = true;
            }
        }

        if ($this->mysqliW->connect_errno) {
            throw new Exception("Failed to connect to MySQL: (".$this->mysqliW->connect_errno.") ".$this->mysqliW->connect_error);
        }

        if ($this->slave === true && $this->mysqliR->connect_errno) {
            throw new Exception("Failed to connect to MySQL: (".$this->mysqliR->connect_errno.") ".$this->mysqliR->connect_error);
        }
    }

    /*
     * Override standard mysqli_prepare to select master/slave server
     * @param $string query
     *
     * @return mysqli_stmt
     */
    public function prepare($query)
    {
        if (stripos($query, "SELECT") && stripos($query, "FOR UPDATE") === false && stripos($query, "INSERT") === false  && $this->slave !== false) {
            $this->lastused = $this->mysqliR;
            return $this->mysqliR->prepare($query);
        } else {
            $this->lastused = $this->mysqliW;
            return $this->mysqliW->prepare($query);
        }
    }

    /*
     * Override standard mysqli_query to select master/slave server
     * @param string $query
     * @param int $resultmode
     *
     * @return boolean
     * @return mixed
     */
    public function query($query, $resultmode = MYSQLI_STORE_RESULT)
    {
        if (stripos($query, "SELECT") && stripos($query, "FOR UPDATE") === false && stripos($query, "INSERT") === false && $this->slave !== false) {/* Use readonly server */
            $this->lastused = $this->mysqliR;
            return $this->mysqliR->query($query, $resultmode);
        } else {
            $this->lastused = $this->mysqliW;
            return $this->mysqliW->query($query, $resultmode);
        }
    }
}
