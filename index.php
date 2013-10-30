<?php
session_start();
ini_set('session.gc_maxlifetime', 600);//сессия живет 10 минут
//ini_set('error_reporting', E_ALL ^ E_NOTICE); //все ошибки с временем исполнения e_notice и строгая компиляция e_strict
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
header('Cache-Control: must-revalidate, max-age=1200');
//header('Pragma: no-cache');
//header('Content-Encoding: gzip');


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
					   function to_file($filename ,$data){
						  
						  if (file_exists($filename)){ 
							   
							$fh = fopen($filename, 'w');
							flock($fh, LOCK_EX);
							fwrite($fh, $data);
						    flock($fh, LOCK_UN);
							fclose($fh);
						    clearstatcache();
						  }
						  else  throw new Exception("файла ".$filename."не существует");
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
							//$doc = new DOMDocument();
							//$doc->load($Style_xml);
							//$doc->schemaValidate($xsd);
							ob_start(function($buffer){ return preg_replace('/(><)/', ">\r\n\t<", $buffer); });//перед выводом табуляция возврат каретки и превод строки

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

				$list[1] = "http://localhost/feed2/models/yr_model.php";				
                $list[2] = "http://localhost/feed2/models/db_feed_model.php";
				$list[3] = "http://localhost/feed2/models/gis_model.php?grab_gismeteo=d1";


		         $curlHandle = curl_multi_init();
						for ($i = 1;$i <= 3; $i++)
								 $curl[$i] = addHandle($curlHandle,$list[$i]);
								  ExecHandle($curlHandle);
                        for ($i = 1;$i <= 3; $i++)
                 {
                       $content[$i] =  curl_multi_getcontent ($curl[$i]);

                 }

				   for ($i = 1;$i <= 3; $i++)//удаляем обработчики
					 curl_multi_remove_handle($curlHandle,$curl[$i]);
				     curl_multi_close($curlHandle);

	

            $data_yr = substr($content[1],11,(strlen($content[1]))-21);		
			//echo $data_yr;									
		    $data_db = $content[2];
		    $data_rosa1600 = $content[3];

			if (is_valid_xml($data_rosa1600) != 1){ $data_rosa1600 = ERROR_Rosa1600; }

      $feed = "<weather>\n$data_db<forecast>$data_rosa1600$data_yr</forecast>
 </weather>";
      
 
      //print $feed;
	    $_SESSION['data_xml'] = $feed;

			try {

					  if(isset($_SESSION['data_xml'])){

							$data = xslt_to_xsd($_SESSION['data_xml'],'xml/style.xsl','xml/schema.xsd');							
							print $data;
							
                            ob_end_flush();
							if (isset($_GET['wr'])) {
							header('Content-Type: text/html; charset=utf-8');
							ob_clean();
							  to_file("feed.xml",$data); 
                               echo "<b>Файл feed.xml обновлен в ".date("Y-m-d h:i:s")."</b>";
							  }
					}

				} catch(Exception $ex) {

					   $mem = memory_get_usage();
					   update_log('log/log.log','пямяти использовано'.$mem);
					   sleep(2);
					   header('Content-Type: text/html; charset=utf-8');
					   echo "<center>Извините, возникла ошибка! ".$ex->getMessage()." cм. логи администратора <br /> для подробностей </center>";
					   unset($_SESSION['data_xml']);
					   session_destroy();
                 }
?>