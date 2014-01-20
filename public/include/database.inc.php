<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
    die('Hacking attempt');

class DatabaseException extends Exception {};
class DatabaseStatement extends PDOStatement { 
    public $num_rows;
    function get_result() {
       return $this;
    }

    function fetch_assoc() {
       $ret = $this->fetch(PDO::FETCH_ASSOC);
       if ($ret) $this->num_rows = 1;
       return $ret;
    }
    function fetch_object() {
       $ret = $this->fetch(PDO::FETCH_OBJ);
       if ($ret) $this->num_rows = 1;
       return $ret;
    }
    function fetch_all() {
       $ret = $this->fetchAll();
       if ($ret) $this->num_rows = 1;
       return  $ret;
    }
 
    function bind_param() {
         if (func_num_args() == 0) return;
         $data = func_get_args();
         $types = array_shift($data); // Shift off first element since that contains the types

         $i=0; 
         foreach (str_split($types) as $type) { 
            switch ($type) {
              case "s": 
                $this->bindValue($i+1, $data[$i], PDO::PARAM_STR); 
              break; 
              case "i": 
                $this->bindValue($i+1, $data[$i], PDO::PARAM_INT); 
              break; 
              default:
                throw new DatabaseException("Unknown param type $type");
               break;
            }
            $i++;
         }
        return true;
    }
}

class Database extends PDO { 
    private $engine; 
    private $host; 
    private $database; 
    private $user; 
    private $pass; 
     
    public function __construct(){ 
        global $config;
        $this->engine = isset($config['db']['driver']) ? $config['db']['driver'] : 'mysql'; 
        $this->host = $config['db']['host']; 
        $this->database = $config['db']['name']; 
        $this->user = $config['db']['user']; 
        $this->pass = $config['db']['pass']; 
        $dns = $this->engine.':dbname='.$this->database.";host=".$this->host; 

        $options = array(
            PDO::ATTR_STATEMENT_CLASS => array('DatabaseStatement'),
        );

        parent::__construct( $dns, $this->user, $this->pass, $options ); 
    }
}
// Instantiate class, we are using mysqlng
//$mysqli = new mysqli($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['name'], $config['db']['port']);

$database = new Database();
/* check connection */
/*if (mysqli_connect_errno()) {
  die("Failed to connect to database");
}
*/
