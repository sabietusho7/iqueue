<?php
 
 
 class DBLayer
 {
     //constants that specify which DB is being used
     private static $SANA_TEST = 1;
     private static $SANA_PRODUCTION = 2;
     
 
     private $HOST;
     private $USER;
     private $PASSWORD;
     private $DB;
 
     private $connection;
 
     /**
      * DBLayer constructor.
      */
     public function __construct()
     {
         $this->chooseDB(self::$SANA_PRODUCTION); //change this accordingly
         $this->connection = mysqli_connect($this->HOST,$this->USER,$this->PASSWORD,$this->DB);
     }
 
     public function getDB()
     {
         return $this->connection;
     }
 
     public function __destruct()
     {
         mysqli_close($this->connection);
     }
 
     private function chooseDB($mode)
     {
         switch ($mode) {
             case self::$SANA_TEST:
                 $this->setDBProperties("localhost", "root", "Sanatusha7", "queue_administration");
                 break;
             case self::$SANA_PRODUCTION:
                 $this->setDBProperties("localhost", "root", "Sanatusha7", "queue_administration");
                 break;
            
             default:
                 die("<h1 style='color: red;'>Could not connect to DB!</h1>");
         }
     }
 
     private function setDBProperties($host, $user, $password, $db) {
         $this->HOST = $host;
         $this->USER = $user;
         $this->PASSWORD = $password;
         $this->DB = $db;
     }
 
     public function executeQuery($query){
         return mysqli_query($this->getDB(),$query);
     }

     public function getRealEscapeString($parameter) {
                return mysqli_real_escape_string($this->getDB(), $parameter);
    }
 
    public function getGeneratedId()
        {
            return mysqli_insert_id($this->getDB());
       }
 }