<?php
namespace wfeed\controllers\DBcontroller;

  interface DBController {
    public static function init();
    	
}


   class DB implements DBController {
    
	  private static $inst = null;
	  private function __construct() {}
	  private function __clone() {} 
	  
	  protected $dsn = 'mysql:host=172.17.100.11;dbname=meteo';
	  protected $user = 'RKH_meteo';
	  protected $pass = 'roza_xutor_1';
	  //private function __wakeup(); //защита от дессиарилизации объекта, нпример для получения десриптора сокета БД
	    
	   public static function init(){
		
		 if(!isset(static::$inst){
		 
		      static::$inst = new DBH($this->dsn, $this->user, $this->pass);
		    
			}
		    return static::$inst;
		 }
	    	 
      }

	  

    class DBH extends \PDO {

	          
	   public function __costruct($dsn, null ,null){
          
		  parent::__construct($dsn, $user, $pass);
		  
		   try {

			 //$dbh = DBconnect::getDBconnect();
	         $this->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			 $this->setAttribute( \PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
			 $this->setAttribute( \PDO::ATTR_EMULATE_PREPARES, false);
			}
				
			catch(PDOException $e)
             {
              throw new Exception("Ошибка подключения к серверу метеоданных ".$e->getMessage());
              }

	   }
	   
	   private function connect()
       {
        $this->link = mysql_connect($this->server, $this->username, $this->password);
        mysql_select_db($this->db, $this->link);
       }
	      
		  public function __sleep()
          {
             return array('server', 'username', 'password', 'db');
          }
    
        public function __wakeup()
        {
             $this->connect();
        }
 }
 //var_dump(DBconnect::getDBconnect());
  /*class DB_controller {

     public static function get_query(){

          $db_inst = DBconnect::getDBconnect();
	      $sql = "CALL meteo_rosa();";

		  $result = $db_inst->query($sql);

         return $result->fetchObject();
	    }
 }*/
 $foo = new DBH();
 //var_dump(new DBconnect());
?>