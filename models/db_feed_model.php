<?php
error_reporting(E_ALL ^ E_NOTICE);
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
						$st1_t = $meteo[0]["TAIR1"];
						$st1_ws = $meteo[0]["VWND1"];
						$st1_wd = $meteo[0]["DWND1"];
						$st1_snow = $meteo[0]["HS11"];


					    $st2_name = $meteo[1]["st_name"];
						$st2_w_dir = $meteo[1]["w_dir"];
						$st2_t = $meteo[1]["TAIR1"];
						$st2_ws = $meteo[1]["VWND1"];
						$st2_wd = $meteo[1]["DWND1"];
						$st2_snow = $meteo[1]["HS11"];

					    $st3_name = $meteo[2]["st_name"];
						$st3_w_dir = $meteo[2]["w_dir"];
						$st3_t = $meteo[2]["TAIR1"];
						$st3_ws = $meteo[2]["VWND1"];
						$st3_wd = $meteo[2]["DWND1"];
						$st3_snow = $meteo[2]["HS11"];


						//Латинские обозначение направления ветра

						$st1_w_dir_lat = replace($st1_w_dir);
					    $st2_w_dir_lat = replace($st2_w_dir);
				        $st3_w_dir_lat = replace($st3_w_dir);

        $aibga =  get_cont("http://www.yr.no/place/Russia/Krasnodar/Khrebet_Aibga/forecast_hour_by_hour.xml");
		$rosa1600 = get_cont('http://localhost/rss_php/gis.php?grab_gismeteo=d1');
		$plato =  get_cont("http://www.yr.no/place/Russia/Krasnodar/Urochishche_Roza/forecast_hour_by_hour.xml");



        //$xml2 = simplexml_load_file($rosa1600, 'SimpleXMLElement', LIBXML_NOWARNING);
		$xml1 = simplexml_load_string($aibga);
        $xml2 = @simplexml_load_string($rosa1600); //шатапим парсинг на случай ошибок данных так как гисметео может быть не доступно
		$xml3 = simplexml_load_string($plato);

		$sky_pik = $xml1->forecast->tabular->time[0]->symbol['name'];
		$t_af_pik = $xml1->forecast->tabular->time[0]->temperature['value'];

		$sky_rosa = $xml2->sky_today;
		$t_af_rosa = $xml2->temp_af;

		//echo $t_af_rosa;

		if ($sky_rosa ==''){$sky_rosa = 'ошибка анализа данных от гисметео';}
		else if	($t_af_rosa =='') {$t_af_rosa = 'ошибка анализа данных от гисметео';}

		$sky_plato = $xml1->forecast->tabular->time[0]->symbol['name'];
		$t_af_plato = $xml3->forecast->tabular->time[0]->temperature['value'];


 $feed = "<currentData last_update='$c_time'>
                     <station name='$st1_name' eng='Rosa Pik'>
                            <sky>$sky_pik</sky>
                            <temp_aft>$t_af_pik</temp_aft>
							<temp>$st1_t</temp>
							<wind_dir lat='$st1_w_dir_lat'>$st1_w_dir</wind_dir>
							<wind_deg>$st1_wd</wind_deg>
							<wind_speed>$st1_ws</wind_speed>
							<snow>$st1_snow</snow>
							<comment></comment>
	                </station>

					<station name='$st2_name' eng='Rosa 1600'>
                            <sky>$sky_rosa</sky>
							<temp_aft>$t_af_rosa</temp_aft>
							<temp>$st2_t</temp>
							<wind_dir lat='$st2_w_dir_lat'>$st2_w_dir</wind_dir>
							<wind_deg>$st2_wd</wind_deg>
							<wind_speed>$st2_ws</wind_speed>
							<snow>$st1_snow</snow>
							<comment></comment>
	                </station>

					 <station name='$st3_name' eng='Rosa Plato'>
							<sky>$sky_plato</sky>
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