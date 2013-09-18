<?php
session_start(); //императивный стиль
ini_set('session.gc_maxlifetime', 600);//сессия живет 10 минут
ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_STRICT); //все ошибки с временем исполнения e_notice и строгая компиляция e_strict
ini_set("log_errors", 1);
ini_set("error_log", "log/log.log");

if($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {ini_set('display_errors','On');} else {ini_set('display_errors','Off');}
session_save_path($_SERVER["DOCUMENT_ROOT"]."/sessions");
$_SESSION['token'] = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']); //хешируем сессию для чексуммы по браузеру и IP пользователя;
if($_SESSION['token'] != md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) die ('Неверная сессия');

if (isset($_SESSION['last_visit'])){
$lat_visit = ((int)((time()-$_SESSION['last_visit'])));

if ($lat_visit < 3) die ('не может быть больше 1 обращения за 3 секунды');

 }
$_SESSION['last_visit'] = time();

header('Content-Type: application/xml');
header('Cache-Control: no-cache');
header('Pragma: no-cache');

define('ERROR_Rosa1600','<station name="rosa1600"><comment>ошибка от гисметео</comment></station>');
define('ERROR_PIK','<station name="pik"><comment>ошибка от yr.no</comment></station>');
define('ERROR_Plato','<station name="plato"><comment>ошибка от yr.no</comment></station>'); 
  
            $D1 =  date('Y-m-d', strtotime(' + 1 days'));
			$D2 =  date('Y-m-d', strtotime(' + 2 days'));
			$D3 =  date('Y-m-d', strtotime(' + 3 days'));
			
			$from_d1_1 = $D1."T07:00:00";
			$from_d1_2 = $D1."T19:00:00";
			$to_d1_1 = $D1."T13:00:00";
			$to_d1_2 = $D2."T01:00:00";
			
			//echo $to_d1_2;
			
			$from_d2_1 = $D2."T16:00:00";
			$from_d2_2 = $D2."T19:00:00";
			$to_d2_1 = $D2."T22:00:00";
			$to_d2_2 = $D3."T01:00:00";  
			
					
		function update_log($log_file, $comment=null){
			
            $log_size = round((filesize($log_file)/1024) ,2);

            if ($log_size >= 500){
               $fh = fopen('log/log.txt','w+') or die ('нет такого файла'); //хендлер для лог файла (путем получения дескриптора файла)				
			   if (flock($fh, LOCK_EX)){ //блокировка файла при коллективном доступе
			
				fwrite($fh, '///лог обновлен://// '.date("Y-m-d h:i:s")."\r\n".$comment."\r\n") or die('Сбой записи');
				flock($fh, LOCK_UN);
			}
				fclose($fh);
				clearstatcache();
			}
               
			   }
			   
			    
		   	function get_between_tags($input, $start_t, $end_t){ 
		 		 				 
				$start_t = str_replace(array('<','>','/'),'',$start_t); //для большей семантики разрешаем в качестве аргументов xml/html теги				
				$end_t = str_replace(array('<','>','/'),'',$end_t);				 
							   
				  if (preg_match('|<'.$start_t.'.*?>(.*)<'.$end_t.'>|si', $input, $arr)) $result = $arr[1]; //perl style
				   
				    else $result = "error";					
							   
				     return $result;
		   		 }
	  	  		    
			 
					
			function is_valid_xml ( $xml ) {
				
					libxml_use_internal_errors( true );					 
					$doc = new DOMDocument('1.0', 'utf-8');					 
					$doc->loadXML( $xml );					 
					$errors = libxml_get_errors();
					 
					return empty( $errors ); //альтернатива isset
				}
					
					function xslt_to_xsd($xml,$xsl,$xsd){ //XLST трансформация для валидации соответсвия xml схеме

                            $xmlDoc = new DOMDocument();
							$xmlDoc->preserveWhiteSpace = false;
							$xmlDoc->formatOutput = true;						
							$xmlDoc->loadXML($xml);						
							$xslDoc = new DomDocument;
							$xslDoc->load($xsl);
							$xsl = new XSLTProcessor;
							$xsl->importStyleSheet($xslDoc);						
							$Style_xml = $xsl->transformToXml($xmlDoc);						
							$doc = new DOMDocument();
							$doc->load($Style_xml);
							$doc->schemaValidate($xsd);
					    
						return $Style_xml;
                            }					
								
			
          function addHandle(&$curlHandle,$url){
                      			
                  $cURL = curl_init();
						curl_setopt($cURL, CURLOPT_URL, $url);
						curl_setopt($cURL, CURLOPT_TIMEOUT, 30);
						curl_setopt($cURL, CURLOPT_FAILONERROR, true);
						curl_setopt($cURL, CURLOPT_HEADER, 0);
						curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);						
						curl_multi_add_handle($curlHandle,$cURL);
						return $cURL;
					}
				
				
			function ExecHandle(&$curlHandle){
							
					$flag=null;
					do {
					//параллельные процессы
						curl_multi_exec($curlHandle,$flag);
					           } while ($flag > 0);
					}
					 								 
				$list[1] = "http://www.yr.no/place/Russia/Krasnodar/Khrebet_Aibga/forecast.xml";
				$list[2] = "http://www.yr.no/place/Russia/Krasnodar/Urochishche_Roza/forecast.xml";
                $list[3] = "http://localhost/feed2/models/db_feed_model.php";
				$list[4] = "http://localhost/feed2/models/gis_model.php?grab_gismeteo=d1";     
	
	       
		         $curlHandle = curl_multi_init();
						for ($i = 1;$i <= 4; $i++)
								 $curl[$i] = addHandle($curlHandle,$list[$i]);
								  ExecHandle($curlHandle);
                        for ($i = 1;$i <= 4; $i++)
                 {
                       $content[$i] =  curl_multi_getcontent ($curl[$i]);
                      
                 }
       	        
				   for ($i = 1;$i <= 4; $i++)//удаляем обработчики
					 curl_multi_remove_handle($curlHandle,$curl[$i]);
				     curl_multi_close($curlHandle);
			
								
			$data_aibga = $content[1];		  	   
			$w_pik_d1 = get_between_tags($data_aibga,'<time from="'.$from_d1_1.'" to="'.$to_d1_1.'" period="1">','<time from="'.$from_d2_1.'" to="'.$to_d2_1.'" period="3">');			   
			if ($w_pik_d1 =='error') {$w_pik_d1 =  get_between_tags($data_aibga,'<time from="'.$from_d1_1.'" to="'.$to_d1_1.'" period="1">','<time from="'.$from_d2_2.'" to="'.$to_d2_2.'" period="3">');}
			else if ($w_pik_d1 =='error') $w_pik_d1 = ERROR_Pik;				
			$v1_pik = "<station name='Rosa Pik'><time for_to='7-13'>".$w_pik_d1."<comment></comment></station>";		   	
       	   						        	
		    $data_plato = $content[2];					
		    $w_plato_d1 =  get_between_tags($data_plato,'<time from="'.$from_d1_1.'" to="'.$to_d1_1.'" period="1">','<time from="'.$from_d2_1.'" to="'.$to_d2_1.'" period="3">');			   
		    if ($w_plato_d1 =='error') {$w_plato_d1 =  get_between_tags($data_plato,'<time from="'.$from_d1_1.'" to="'.$to_d1_1.'" period="1">','<time from="'.$from_d2_2.'" to="'.$to_d2_2.'" period="3">');}
		    else if ($w_plato_d1 =='error') $w_plato_d1 = ERROR_Plato;				
		    $v1_plato = "<station name='Rosa Plato'><time for_to='7-13'>".$w_plato_d1."<comment></comment></station>";
		   
		   
		    $data_db = $content[3];						
		    $data_rosa1600 = $content[4];		   
		   		   			
			if (is_valid_xml($data_rosa1600) != 1){ $data_rosa1600 = ERROR_Rosa1600; }					  
		
      $feed = "<?xml version='1.0' encoding='utf-8'?><weather>$data_db"."<forecast>$v1_pik"."$data_rosa1600"."$v1_plato"."</forecast></weather>";
     // print $feed;		
	  $_SESSION['data_xml'] = $feed;  
		 				
			try {
						  
					  if(isset($_SESSION['data_xml'])){
						 							
							$data = xslt_to_xsd($_SESSION['data_xml'],'xml/style.xsl','xml/schema.xsd');
							print $data;				
						 					  
					   } 
					   
				} catch(Exception $ex) {
					
					   $mem = memory_get_usage();
					   update_log('log/log.log','пямяти использовано'.$mem);
					   sleep(1);	   
					   header('Content-Type: text/html');
					   echo "<center>Извините, возникла ошибка! ".$ex->getMessage()." cм. логи администратора <br /> для подробностей </center>";
					   unset($_SESSION['data_xml']);
					   session_destroy();   
	}
?>