<?php
/**
* Registry storage of apllication
* Saving data from meteo server on different storage
* Caching data on the time interval

*/
namespace wfeed\controlers\Storage;

 interface Storage { 
 
	  public static function cache($data, $interval);
	  
	}

  abstract class Registry {

    abstract protected function get($key);
    abstract protected function set($key, $val);

    public function __get($key) {
   // Проверяем есть ли метод с подобным именем
    if (method_exists($this, $method = 'get_'.$key) ) {
     return $this->$method();
  // Метод не найден, то проверяем есть ли такой атрибут у объекта и возвращаем его
    } elseif(property_exists($this, $property = '_'.$key)){
      return $this->$property;
  // В противном случае регистрируем ошибку
    } else {
     throw new Exception("Свойство ".$key." не доступно для класса ".__CLASS__);
    }
  }

}

  class MemAppRegistry extends Registry implements Storage {

     private static $inst = null;
	 private $id;

	  public static function getInstance(){

	    if( ! isset(self::$inst) ) {
		 self::$inst = new MemAppRegistry();
		 }
        return self::$inst;

	  }
         private function __construct() {

		   $this->id = @shm_attach(55, 10000, 0666);
		     if (!$this->id) {

			  throw new Exception ("Нет доступа к разделяемой памяти");
		     }
		   }

		  private function __clone() {}

			public function get($key){
			 return shm_get_var($this->id, $key);

			  }

            public function set($key=null, $val=null){

			   $shm_mem = shm_attach(2008, 10000);

               $s1= shm_put_var($shm_mem, 1430, 'Hello World');

              shm_detach($shm_mem);
			  return $s1;
              }

  }

  
      class SesiionRegistry extends Registry {
   
        }

      try {

     $reg = MemAppRegistry::getInstance();

	 $pdo = new \PDO('mysql:host=172.17.100.11;dbname=meteo', 'RKH_meteo', 'roza_xutor_1');
     $reg->set('db','sdsdsdsd');
      }
	  catch (PDOExceptions $e){
	  throw new Exception('Ошибка '.$e->getMessage());
	  }
?>