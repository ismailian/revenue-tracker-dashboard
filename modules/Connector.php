<?php


/**
 * ----------------------------------------------------------------
 * ----------------------------------------------------------------
 * This class is made for database Connections:                   -
 * It eases the process of (Selecting, Inserting, Updating,       -
 * Deleting) data without having redandent code all over.         -
 * ----------------------------------------------------------------
 
 * -------------------   Universal Params  ------------------------
 * table_name  : the target table name.
 * where       : data to locate the target Row(s).
 * fetchOnly   : fields to fetch instead of All.
 * data        : data to insert or update with.
 * ----------------------------------------------------------------
 
 * ----------------     Fetch Methods    --------------------------
 * ----------------------------------------------------------------
 * methods: getOne() / getMany()
 * params: table_name, where, fetchOnly
 * ----------------------------------------------------------------
 
 * -------------------   Update Methods  --------------------------
 * ----------------------------------------------------------------
 * method: patch()
 * params: table_name, where, data
 * ----------------------------------------------------------------
 
 * -----------------  Data Revoke Methods  ------------------------
 * ----------------------------------------------------------------
 * method: revoke()
 * params: table_name, where
 * ----------------------------------------------------------------
 
 * -----------------  Table Reset Methods  ------------------------
 * ----------------------------------------------------------------
 * method: empty()
 * params: table_name
 * ----------------------------------------------------------------
 */


# TABLES NAMES
# those are demo tables only,
# if you don't have them on your db
# just replace them with your own.
define('USER',         'users');
define('ACCESS',       'access');
define('PRODUCTS',     'products');
define('DATES',        'dates');
define('CALCULATOR',   'calculator');
define('FEES',         'fees');
define('FEE_TYPE',     'fee_category');
define('DATE_TYPE',    'date_category');
define('PRODUCT_TYPE', 'product_type');
define('TMP',          'tmp_calc');



class Connector
{

   # DATABASE INFO:
   private $host = "localhost";  // hostname
   private $user = "root";       // username
   private $pass = "";           // password
   private $name = "";           // database

   # OTHER VARIABLES:
   private $connection;
   private $result;
   public $count = 0;
   public $returnObj = false;
   public $dumpQuery = false;


   function __construct($host, $user, $pass, $name)
   {

      $this->host = $host;
      $this->user = $user;
      $this->pass = $pass;
      $this->name = $name;

      # here we connect to database:
      $resource = mysqli_connect($this->host, $this->user, $this->pass, $this->name);

      if ($resource) {
         $this->connection = $resource;
      } else {
         die("Connection Failure!");
      }

   }

   // SANITIZE VARIABLES:
   function __sanitize($text)
   {
      $result = trim(mysqli_real_escape_string($this->connection, $text));
      return htmlspecialchars($result);
   }

   // INITIATE THE QUERY:
   private function send(String $cmd)
   {
      $this->__dispose();
      $this->dumpQuery ? die($cmd) : null;
      $resource = $this->connection->query($cmd);
      
      if (is_bool($resource)) {
         $this->result = $resource;
      } else {
      
         $this->count = ($resource)->num_rows;

         if ($this->returnObj) {

            // EXTRA INFO:
            // $this->result = ($resource)->fetch_object(); // returns One Row in (Object Format)
            // $this->result = ($resource)->fetch_assoc(); // returns One Row in (Assoc-Array Format)
            // $this->result = mysqli_fetch_all($resource, MYSQLI_ASSOC); // returns All Rows in (Assoc-Array Format)

            // RETURNS ALL ROWS IN OBJECT FORMAT
            $this->result = array();

            array_map(function ($r) {
               array_push($this->result, (object) $r);
            }, mysqli_fetch_all($resource, MYSQLI_ASSOC));
      
         } else {
      
            $this->result[] = ($resource)->fetch_all(MYSQLI_ASSOC);
      
         }
      }

   }

   // GET ONE DATA ENTRY:
   function getOne(String $tblname, array $where, array $fetchOnly = null, String $operator = null)
   {
      $cmd = "SELECT * FROM {$tblname} WHERE ";

      // >> Checking for FetchOnly Params:
      if (!is_null($fetchOnly)) {
         $keys = "";
         foreach ($fetchOnly as $key) {
            $keys .= "{$key}, ";
         }
         $cmd = str_replace('*', substr($keys, 0, strrpos($keys, ", ")), $cmd);
      }

      // Looping through Params:
      foreach ($where as $key => $value) {
         $cmd .= strtoupper($key) . "='" . $this->__sanitize($value) . "' AND ";
      }
      if (!is_null($operator)) {
         $cmd = str_replace('=', $operator, $cmd);
      }

      $cmd = substr($cmd, 0, strrpos($cmd, " AND "));
      // >> Send Query and return Results:
      $this->send($cmd);
      return ($this->count > 0) ? $this->result[0] : $this->result;
   }

   // GET ALL DATA ENTRIES:
   function getMany(String $tblname, array $where = null, array $fetchOnly = null, array $operator = null)
   {
      $cmd = "SELECT * FROM {$tblname}";
      if (!is_null($fetchOnly)) { // >> Checking for FetchOnly Params:
         $keys = "";
         foreach ($fetchOnly as $key) {
            $keys .= "{$key}, ";
         }
         $cmd = str_replace('*', substr($keys, 0, strrpos($keys, ", ")), $cmd);
      }
      if (!is_null($where)) { // >> Looping Through Params:
         $cmd .= " WHERE ";
         $index = 0;
         foreach ($where as $key => $value) {

            $opr = "";
            if (strpos($key, ".") !== false) {
               $opr = $operator[$key];
               $key = explode(".", $key)[1];
            }

            if ($value === '!') {
               $cmd .= "!" . strtoupper($key) . " AND ";
            } else {
               if (!$operator == null) {
                  if ($opr) {
                     $cmd .= strtoupper($key) . " {$opr} '" . $this->__sanitize($value) . "' AND ";
                  } else {

                     $cmd .= strtoupper($key) . " {$operator[$key]} '" . $this->__sanitize($value) . "' AND ";
                  }
               } else {
                  $cmd .= strtoupper($key) . " = '" . $this->__sanitize($value) . "' AND ";
               }
            }
         }
         $cmd = substr($cmd, 0, strrpos($cmd, " AND "));
      }
      $this->send($cmd); // >> Sending Query and Returning Results:
      return $this->result;
   }

   // PUT NEW DATA ENTRY:
   function put(String $tblname, array $data)
   {
      $cmd = "INSERT INTO {$tblname} (_k_) VALUES (_v_)";
      $key = "";
      $value = "";
      foreach ($data as $k => $v) {
         $key .= ("{$k}, ");
         $value .= ("'{$this->__sanitize($v)}', ");
      }

      $cmd = str_replace("_k_", substr($key, 0, strrpos($key, ", ")), $cmd);
      $cmd = str_replace("_v_", substr($value, 0, strrpos($value, ", ")), $cmd);

      // Send query and return results:
      $this->send($cmd);
      return $this->result;
   }

   // UPDATE EXISTING DATA ENTRY:
   function patch(String $tblname, array $where, array $data)
   {
      $cmd = "UPDATE {$tblname} SET ";
      $cmd2 = "";
      $cmd3 = "";
      foreach ($data as $key => $value) {
         $value = $this->__sanitize($value);
         if ($value === '?') {
            $cmd2 .= strtoupper($key) . "=!" . strtoupper($key) . ", ";
         } else {
            $cmd2 .= strtoupper($key) . "='{$value}'" . ", ";
         }
      }
      foreach ($where as $key => $value) {
         $value = $this->__sanitize($value);
         $cmd3 .= strtoupper($key) . "='{$value}'" . " AND ";
      }

      # The above (params/data) loops result in a trailing
      # (comma & a concatination operator) thus we remove them below..!
      $cmd2 = substr($cmd2, 0, strrpos($cmd2, ", "));
      $cmd3 = substr($cmd3, 0, strrpos($cmd3, " AND "));
      $cmd .= "{$cmd2} WHERE {$cmd3}";

      // send query and Return Results:
      $this->send($cmd);
      return $this->result;
   }

   // DELETE DATA ENTRY:
   function revoke(String $tblname, array $where)
   {
      $cmd = "DELETE FROM {$tblname} WHERE ";
      foreach ($where as $key => $value) {
         $value = $this->__sanitize($value);
         $cmd .= strtoupper($key) . "='$value' AND ";
      }
      $this->send(substr($cmd, 0, strrpos($cmd, " AND ")));
      return $this->result;
   }

   // TRUNCATE A TABLE:
   function empty(String $tblname)
   {
      $cmd = ("TRUNCATE {$tblname}");
      $this->send($cmd);
      return $this->result;
   }

   // RETURN RESULTS:
   function getResult()
   {
      return !is_null($this->result) ? $this->result : null;
   }

   // DESTROY STORED DATA:
   private function __dispose()
   {
      $this->result = null;
      $this->count = 0;
   }
}
