-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`RKH_meteo`@`%` PROCEDURE `meteo_rosa`()
    SQL SECURITY INVOKER
BEGIN  



(SELECT 

 
  `TmStamp`,
  `RecNum`,  
 
       CASE 
         WHEN station  = 12 THEN 'Роза Пик'
         WHEN station  = 14 THEN 'Роза 1600'
         WHEN station  = 17 THEN 'Плато'
         END AS st_name,
         
              CASE 
         WHEN DWND  >= 0 AND DWND  <= 5  THEN 'C'
         WHEN DWND  >= 5 AND DWND  <= 40  THEN 'С-СВ'
         WHEN DWND  >= 40 AND DWND  <= 50  THEN 'CВ'
         WHEN DWND  >= 50 AND DWND  <= 85  THEN 'В-СВ'
         WHEN DWND  >= 85 AND DWND  <= 95  THEN 'В'
         WHEN DWND  >= 95 AND DWND  <= 118  THEN 'В-ЮВ'
         WHEN DWND  >= 118 AND DWND  <= 140  THEN 'ЮВ'
         WHEN DWND  >= 140 AND DWND  <= 170  THEN 'Ю-ЮВ'
         WHEN DWND  >= 170 AND DWND  <= 190  THEN 'Ю'
         WHEN DWND  >= 190 AND DWND  <= 210  THEN 'Ю-ЮЗ'
         WHEN DWND  >= 235 AND DWND  <= 260  THEN 'З-ЮЗ'
         WHEN DWND  >= 260 AND DWND  <= 280  THEN 'З'
         WHEN DWND  >= 280 AND DWND  <= 300  THEN 'З-СЗ'
         WHEN DWND  >= 300 AND DWND  <= 320  THEN 'СЗ'
         WHEN DWND  >= 320 AND DWND  <= 355  THEN 'С-СЗ'
         WHEN DWND  >= 355 AND DWND  <= 360  THEN 'С'
         
         END AS w_dir,
            
      CASE 
         WHEN hs1  = 6998 THEN 'Ошибка сенсора'
         WHEN hs1  = 6999 THEN 'Нет сенсора'
          ELSE HS1
         END AS HS11,
         
         CASE 
         WHEN TAIR  = 6998 THEN 'Ошибка сенсора'
         WHEN TAIR  = 6999 THEN 'Нет сенсора'
          ELSE TAIR
         END AS TAIR1,
         
         CASE 
         WHEN DWND  = 6998 THEN 'Ошибка сенсора'
         WHEN DWND  = 6999 THEN 'Нет сенсора'
          ELSE DWND
         END AS DWND1,
         
         CASE 
         WHEN VWND  = 6998 THEN 'Ошибка сенсора'
         WHEN VWND  = 6999 THEN 'Нет сенсора'
          ELSE VWND
         END AS VWND1
         
   
FROM 
  `rkhu2_meteo_108` ORDER BY TmStamp DESC LIMIT 1)
  
UNION

(SELECT 


  `TmStamp`,
  `RecNum`,  
 
       CASE 
         WHEN station  = 12 THEN 'Роза Пик'
         WHEN station  = 14 THEN 'Роза 1600'
         WHEN station  = 17 THEN 'Плато'
         END AS st_name,
         
               CASE 
         WHEN DWND  >= 0 AND DWND  <= 5  THEN 'C'
         WHEN DWND  >= 5 AND DWND  <= 40  THEN 'С-СВ'
         WHEN DWND  >= 40 AND DWND  <= 50  THEN 'CВ'
         WHEN DWND  >= 50 AND DWND  <= 85  THEN 'В-СВ'
         WHEN DWND  >= 85 AND DWND  <= 95  THEN 'В'
         WHEN DWND  >= 95 AND DWND  <= 118  THEN 'В-ЮВ'
         WHEN DWND  >= 118 AND DWND  <= 140  THEN 'ЮВ'
         WHEN DWND  >= 140 AND DWND  <= 170  THEN 'Ю-ЮВ'
         WHEN DWND  >= 170 AND DWND  <= 190  THEN 'Ю'
         WHEN DWND  >= 190 AND DWND  <= 210  THEN 'Ю-ЮЗ'
         WHEN DWND  >= 235 AND DWND  <= 260  THEN 'З-ЮЗ'
         WHEN DWND  >= 260 AND DWND  <= 280  THEN 'З'
         WHEN DWND  >= 280 AND DWND  <= 300  THEN 'З-СЗ'
         WHEN DWND  >= 300 AND DWND  <= 320  THEN 'СЗ'
         WHEN DWND  >= 320 AND DWND  <= 355  THEN 'С-СЗ'
         WHEN DWND  >= 355 AND DWND  <= 360  THEN 'С'
         
          END AS w_dir,
            
      CASE 
         WHEN hs1  = 6998 THEN 'Ошибка сенсора'
         WHEN hs1  = 6999 THEN 'Нет сенсора'
          ELSE HS1
         END AS HS11,
         
         CASE 
         WHEN TAIR  = 6998 THEN 'Ошибка сенсора'
         WHEN TAIR  = 6999 THEN 'Нет сенсора'
          ELSE TAIR
         END AS TAIR1,
         
         CASE 
         WHEN DWND  = 6998 THEN 'Ошибка сенсора'
         WHEN DWND  = 6999 THEN 'Нет сенсора'
          ELSE DWND
         END AS DWND1,
         
         CASE 
         WHEN VWND  = 6998 THEN 'Ошибка сенсора'
         WHEN VWND  = 6999 THEN 'Нет сенсора'
          ELSE VWND
         END AS VWND1
      
   
   
FROM 
  `rkhu4_meteo_108` ORDER BY TmStamp DESC LIMIT 1)
  
UNION

(SELECT 


  `TmStamp`,
  `RecNum`,  

       CASE 
         WHEN station  = 12 THEN 'Роза Пик'
         WHEN station  = 14 THEN 'Роза 1600'
         WHEN station  = 17 THEN 'Плато'
         END AS st_name,
         
               CASE 
         WHEN DWND  >= 0 AND DWND  <= 5  THEN 'C'
         WHEN DWND  >= 5 AND DWND  <= 40  THEN 'С-СВ'
         WHEN DWND  >= 40 AND DWND  <= 50  THEN 'CВ'
         WHEN DWND  >= 50 AND DWND  <= 85  THEN 'В-СВ'
         WHEN DWND  >= 85 AND DWND  <= 95  THEN 'В'
         WHEN DWND  >= 95 AND DWND  <= 118  THEN 'В-ЮВ'
         WHEN DWND  >= 118 AND DWND  <= 140  THEN 'ЮВ'
         WHEN DWND  >= 140 AND DWND  <= 170  THEN 'Ю-ЮВ'
         WHEN DWND  >= 170 AND DWND  <= 190  THEN 'Ю'
         WHEN DWND  >= 190 AND DWND  <= 210  THEN 'Ю-ЮЗ'
         WHEN DWND  >= 235 AND DWND  <= 260  THEN 'З-ЮЗ'
         WHEN DWND  >= 260 AND DWND  <= 280  THEN 'З'
         WHEN DWND  >= 280 AND DWND  <= 300  THEN 'З-СЗ'
         WHEN DWND  >= 300 AND DWND  <= 320  THEN 'СЗ'
         WHEN DWND  >= 320 AND DWND  <= 355  THEN 'С-СЗ'
         WHEN DWND  >= 355 AND DWND  <= 360  THEN 'С'
         
            END AS w_dir,
            
    CASE 
         WHEN hs1  = 6998 THEN 'Ошибка сенсора'
         WHEN hs1  = 6999 THEN 'Нет сенсора'
          ELSE HS1
         END AS HS11,
         
         CASE 
         WHEN TAIR  = 6998 THEN 'Ошибка сенсора'
         WHEN TAIR  = 6999 THEN 'Нет сенсора'
          ELSE TAIR
         END AS TAIR1,
         
         CASE 
         WHEN DWND  = 6998 THEN 'Ошибка сенсора'
         WHEN DWND  = 6999 THEN 'Нет сенсора'
          ELSE DWND
         END AS DWND1,
         
         CASE 
         WHEN VWND  = 6998 THEN 'Ошибка сенсора'
         WHEN VWND  = 6999 THEN 'Нет сенсора'
          ELSE VWND
         END AS VWND1
         
   
FROM 
  `rkhu7_meteo_108` ORDER BY TmStamp DESC LIMIT 1); 
   
    
END