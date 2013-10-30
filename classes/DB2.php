<?php
namespace wfeed\controllers\db;

class DBconnect extends PDO {

 public $dsn = 'mysql:host=172.17.100.11;dbname=meteo';
 public $user = 'RKH_meteo';
 public $pass = 'roza_xutor_1';

 protected static $inst = null;
 protected function __clone() { return false; }
 protected function __wakeup() { return false; }
 protected function __destruct() {}

     public function getDBconnect(){
       if(isset(static::$inst)){      //используем позднее статическое связывание и шаблон одиночку

		static::$inst = new DBconnect();
	    }
       return static::$inst;
    }

	   private function __costruct(){

		  try {

			$dbc = new DBconnect($this->dsn, $this->user, $this->pass);
	        $dbc->setAttribute(DBconnect::ATTR_ERRMODE, DBconnect::ERRMODE_EXCEPTION, DBconnect::ATTR_DEFAULT_FETCH_MODE, DBconnect::FETCH_OBJ);
			$dbc->setAttribute(DBconnect::ATTR_EMULATE_PREPARES, false);
			 }

			catch(PDOException $e)
             {
            echo "Ошибка подключения к серверу метеоданных ".$e->getMessage();
              }

	   }
 }

  class DB_controller {

     public static function get_query(){

          $db_inst = new DBconnect();
	      $sql = "CALL meteo_rosa();";

		  $result = $db_inst->query($sql);

         return $result;
	    }
 }
 echo var_dump(DB_controller::get_query());
?>