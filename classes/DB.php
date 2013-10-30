<?php
//Использую расширение PEAR MDB2 для отделения логики от версии СУБД
require_once 'MDB2.php';
//Класс основанный на Singleton для однократного вызова экземпляра(т.е. подключения к БД) и многократного использования объектов

	function get_cont($url) {
		try {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_HEADER, false);			
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_TIMEOUT, 3);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


					$feedContent = curl_exec($curl);
						curl_close($curl);
						}
					catch (Exception $e){ echo 'Ошибка грабера: ', $e->getMessage(), "\n"; }
						return $feedContent;
						}

class DB {


	public	$dsn = 'mysql://RKH_meteo:roza_xutor_1@172.17.100.11/meteo';
    public $options = array(
    'debug' => 2,
    'result_buffering' => false,
     );

        protected static $_instance;


        public static function getInstance() {
            if (self::$_instance === null) {
                self::$_instance = new self;
            }
            return self::$_instance;
        }


        private  function __construct() { // конструктор отрабатывает один раз при вызове DB::getInstance();

             $mdb2 = MDB2::connect($this->dsn, $this->options);
                if (PEAR::isError($mdb2)) {
                 die("Невозможно установить соединение c БД ".$mdb2->getMessage());
                 }
			 $this->connect = $mdb2;
		$mdb2->setCharset('utf8');

		    $this->query('SET NAMES utf8');

                 }

        private function __clone() {
        }

        private function __wakeup() {
        }

	  /*function __destruct() {

	   $obj=self::$_instance;
       $v1 = $obj->connect;
	    $v1->disconnect();
		}*/



        public static function query($sql) {

            $obj=self::$_instance;

            if(isset($obj->connect)){

                $result=mysql_query($sql)or die("<br/><span style='color:red'>Ошибка в SQL запросе:</span> ".mysql_error());

                return $result;
            }
            return false;
        }


        public static function fetch_object($object)
        {
            return @mysql_fetch_object($object);
        }


        public static function fetch_array($object)
        {

            return @mysql_fetch_array($object);
        }

      public static function disconn()
        {
            $obj=self::$_instance;
            $v1 = $obj->connect;
	        $v1->disconnect();
        }


  }
  class Conditions {

   public function getTodayConditions(){

      DB::getInstance(); //получаем единственный экземпляр(Singleton)класса для подключения к БД

      $result=DB::query("CALL meteo_rosa();");//вызываем хранимую процедуру(перенос части логики на СУБД) в mySQL
	  $rows = mysql_num_rows($result);

	  for ($j=0; $j<$rows; $j++)
	            {

	     $meteo[] = DB::fetch_array($result);// получаем данные в виде массива в скалярную переменую $meteo

                }

      DB::disconn();//отключаемся от БД
  	  return $meteo;
    }

 }
?>