<?php
//error_reporting(E_ALL ^ E_DEPRECATED);
error_reporting(0);
header("Content-Type: application/xml");
require_once '../classes/DB.php';

         function replace($path){
						 $search = array ("'С'", "'Ю'", "'З'", "'В'","'C'");
						 $replace= array ("N", "S", "W", "E","N" );
						 return preg_replace ($search,$replace,$path);
						}

				$cond = new  Conditions();
				$meteo = $cond->getTodayConditions();//получаем данные

				        $c_time = $meteo[0]["TmStamp"];

			            $st1_name = $meteo[0]["st_name"];
						$st1_w_dir = $meteo[0]["w_dir"];
						$st1_t = round($meteo[0]["TAIR1"],0);
						$st1_ws = round($meteo[0]["VWND1"],0);
						$st1_wd = $meteo[0]["DWND1"];
						$st1_snow = round($meteo[0]["HS11"],0);


					    $st2_name = $meteo[1]["st_name"];
						$st2_w_dir = $meteo[1]["w_dir"];
						$st2_t = round($meteo[1]["TAIR1"],0);
						$st2_ws = round($meteo[1]["VWND1"],0);
						$st2_wd = $meteo[1]["DWND1"];
						$st2_snow = round($meteo[1]["HS11"],0);

					    $st3_name = $meteo[2]["st_name"];
						$st3_w_dir = $meteo[2]["w_dir"];
						$st3_t = round($meteo[2]["TAIR1"],0);
						$st3_ws = round($meteo[2]["VWND1"],0);
						$st3_wd = $meteo[2]["DWND1"];
						$st3_snow = round($meteo[2]["HS11"],0);


						//Латинские обозначение направления ветра

						$st1_w_dir_lat = substr(replace($st1_w_dir),0,1);
					    $st2_w_dir_lat = substr(replace($st2_w_dir),0,1);
				        $st3_w_dir_lat = substr(replace($st3_w_dir),0,1);
												

        $sky_feed =  get_cont("http://localhost/feed2/models/yr_model.php");
		$rosa1600 = get_cont('http://localhost/feed2/models/gis_model.php?grab_gismeteo=d1');
		

		/*if ($st1_t > 0){
		$st1_t = "+".$st1_t;
		}

        if ($st2_t > 0){
		$st2_t = "+".$st2_t;
		}

		if ($st3_t > 0){
		$st3_t = "+".$st3_t;
		}*/

        //$xml2 = simplexml_load_file($rosa1600, 'SimpleXMLElement', LIBXML_NOWARNING);
		$xml1 = simplexml_load_string($sky_feed);
        $xml2 = @simplexml_load_string($rosa1600); //шатапим парсинг на случай ошибок данных так как гисметео может быть не доступно
		
        $sky_pik = $xml1->station->time[0]->symbol['name'];
		$sky_pik_id = $xml1->station->time[0]->symbol['id'];
		$t_af_pik = $xml1->station->time[1]->temperature['value'];
				
		$sky_plato = $xml1->station[1]->time[0]->symbol['name'];
		$sky_plato_id = $xml1->station[1]->time[0]->symbol['id'];
		$t_af_plato = $xml1->station[1]->time[1]->temperature['value'];
		
		
		$sky_rosa = $xml2->sky_today;
		$sky_rosa_id = $xml2->sky_today['id'];
		$t_af_rosa = $xml2->temp_af;

		//echo $t_af_rosa;

		if ($sky_rosa ==''){$sky_rosa = 'ошибка анализа данных от гисметео';}
		else if	($t_af_rosa =='') {$t_af_rosa = 'ошибка анализа данных от гисметео';}

	//лучше отделить представление от модели
$feed = "<currentData last_update='$c_time'>\t
                     <station name='$st1_name' eng='Роза Пик'>
                            <sky id='$sky_pik_id'>$sky_pik</sky>
                            <temp_aft>$t_af_pik</temp_aft>
							<temp>$st1_t</temp>
							<wind_dir lat='$st1_w_dir_lat'>$st1_w_dir</wind_dir>
							<wind_deg>$st1_wd</wind_deg>
							<wind_speed>$st1_ws</wind_speed>
							<snow>$st1_snow</snow>
							<comment></comment>
	                </station>

					<station name='$st2_name' eng='Роза 1600'>
                            <sky id='$sky_rosa_id'>$sky_rosa</sky>
							<temp_aft>$t_af_rosa</temp_aft>
							<temp>$st2_t</temp>
							<wind_dir lat='$st2_w_dir_lat'>$st2_w_dir</wind_dir>
							<wind_deg>$st2_wd</wind_deg>
							<wind_speed>$st2_ws</wind_speed>
							<snow>$st1_snow</snow>
							<comment></comment>
	                </station>

					 <station name='$st3_name' eng='Роза Плато-Роза Стадион'>
							<sky id='$sky_plato_id'>$sky_plato</sky>
                            <temp_aft>$t_af_plato</temp_aft>
							<temp>$st3_t</temp>
							<wind_dir lat='$st3_w_dir_lat'>$st3_w_dir</wind_dir>
							<wind_deg>$st3_wd</wind_deg>
							<wind_speed>$st3_ws</wind_speed>
							<snow>$st3_snow</snow>
                            <comment></comment>
	                 </station>
	</currentData>";
print $feed;
?>