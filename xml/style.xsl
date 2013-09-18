<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:rh="uri:rosa_khutor_resort" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="sh1.xsd">
<xsl:output method="xml" encoding="utf-8" indent="no" omit-xml-declaration="no"/> 

	
	<xsl:template match="weather">
	
		 <rh:weather>
		  <xsl:apply-templates/>		 									
	     </rh:weather> 
	  
    </xsl:template>	
					
			  
			
  <xsl:template match="currentData"> 
  		<xsl:comment>data from meteo station in real time</xsl:comment>	
   <rh:current>
	<xsl:attribute name="last_update"><xsl:value-of select="@last_update"/></xsl:attribute>
                 	
	 <xsl:for-each select="station">	  
	    <xsl:variable name="title" select="@eng" />         
	      
		  <rh:station><xsl:attribute name="name"><xsl:value-of select="$title"/></xsl:attribute>				
			
			<rh:sky><xsl:value-of select="sky"/></rh:sky>
				<xsl:choose>
						<xsl:when test="temp = 'Ошибка сенсора'"><rh:temp_now>Ошибка сенсора (Sensor error)</rh:temp_now></xsl:when>
						<xsl:when test="temp = 'Нет сенсора'"><rh:temp_now>Нет сенсора (Sensor not installed)</rh:temp_now></xsl:when>
						<xsl:otherwise><rh:temp_now><xsl:value-of select="temp"/></rh:temp_now> </xsl:otherwise>
			   	</xsl:choose>
				
				       <rh:temp_afternoon><xsl:value-of select="temp_aft"/></rh:temp_afternoon>												
                       <rh:wind_dir lat="{wind_dir/@lat}"><xsl:value-of select="wind_dir" /></rh:wind_dir>				
					   <rh:wind_deg><xsl:value-of select="wind_deg" /></rh:wind_deg>
					   
					 <xsl:choose>
						<xsl:when test="wind_speed = 'Ошибка сенсора'"><rh:wind_speed>Ошибка сенсора (Sensor error)</rh:wind_speed></xsl:when>
						<xsl:when test="wind_speed = 'Нет сенсора'"><rh:wind_speed>Нет сенсора (Sensor not installed)</rh:wind_speed></xsl:when>
						<xsl:otherwise><rh:wind_speed><xsl:value-of select="wind_speed"/></rh:wind_speed></xsl:otherwise>					
			         </xsl:choose>
					   
				<xsl:choose>
						<xsl:when test="snow = 'Ошибка сенсора'"><rh:snow>Ошибка сенсора (Sensor error)</rh:snow></xsl:when>
						<xsl:when test="snow = 'Нет сенсора'"><rh:snow>Нет сенсора (Sensor not installed)</rh:snow></xsl:when>
						<xsl:otherwise><rh:snow><xsl:value-of select="snow"/></rh:snow> </xsl:otherwise>
			   	</xsl:choose>
               			<rh:comment><xsl:value-of select="comment"/></rh:comment>	
			  </rh:station>			               					      
			 </xsl:for-each> 
	        </rh:current>	
		</xsl:template>
		  	
			
			
		<xsl:template match="forecast">
			<xsl:comment>forecast weathter data from external source</xsl:comment>	
			<rh:forecast>
			  <xsl:for-each select="station">	  
	            <xsl:variable name="title" select="@name" />         
	      
		          <rh:station><xsl:attribute name="name"><xsl:value-of select="$title"/></xsl:attribute>
			  
			         <rh:period for="D1">
					 
					   <rh:sky><xsl:value-of select="time[1]/symbol/@name" /></rh:sky>
					   <rh:temp_mon><xsl:value-of select="time[1]/temperature/@value" /></rh:temp_mon>
					   <rh:temp_afternoon><xsl:value-of select="time[2]/temperature/@value" /></rh:temp_afternoon>
					   
						<xsl:variable name="v1" select="time[1]/windDirection/@code" />
						  <xsl:variable name="wind_cyril_Pik">
							   <xsl:choose>
						  
								  <xsl:when test="$v1 = 'N'">C</xsl:when>
								  <xsl:when test="$v1 = 'S'">Ю</xsl:when>
								  <xsl:when test="$v1 = 'E'">В</xsl:when>
								  <xsl:when test="$v1 = 'W'">З</xsl:when>
								  <xsl:when test="$v1 = 'SW'">ЮЗ</xsl:when>
								  <xsl:when test="$v1 = 'WSW'">З-ЮЗ</xsl:when>
								  <xsl:when test="$v1 = 'WNW'">З-CЗ</xsl:when>
								  <xsl:when test="$v1 = 'ENE'">В-СВ</xsl:when>
							 
								<xsl:otherwise>
								   <rh:wind_dir lat="{time[1]/windDirection/@code}"><xsl:copy-of select="time[1]/windDirection/@code" /></rh:wind_dir>
								 </xsl:otherwise>
							   </xsl:choose>
							 </xsl:variable>
						  				     
					   
					   <rh:wind_dir lat="{time[1]/windDirection/@code}"><xsl:value-of select="$wind_cyril_Pik" /></rh:wind_dir>
                       <rh:wind_deg><xsl:value-of select="time[1]/windDirection/@deg" /></rh:wind_deg>					   
					   <rh:wind_speed><xsl:value-of select="time[1]/windSpeed/@mps" /></rh:wind_speed>
					   <rh:comment></rh:comment>					   		   
					   
			         </rh:period>
					 
					 
			        <rh:period for="D2">
					 
					 <rh:sky><xsl:value-of select="time[5]/symbol/@name" /></rh:sky>
					   <rh:temp_mon><xsl:value-of select="time[5]/temperature/@value" /></rh:temp_mon>
					   <rh:temp_afternoon><xsl:value-of select="time[6]/temperature/@value" /></rh:temp_afternoon>
					   
							<xsl:variable name="v2" select="time[5]/windDirection/@code" />
								<xsl:variable name="wind_cyril_Plato">
								<xsl:choose>
							  
									  <xsl:when test="$v2 = 'N'">C</xsl:when>
									  <xsl:when test="$v2 = 'S'">Ю</xsl:when>
									  <xsl:when test="$v2 = 'E'">В</xsl:when>
									  <xsl:when test="$v2 = 'W'">З</xsl:when>
									  <xsl:when test="$v2 = 'SW'">ЮЗ</xsl:when>
									  <xsl:when test="$v2 = 'WSW'">З-ЮЗ</xsl:when>
									  <xsl:when test="$v2 = 'WNW'">З-CЗ</xsl:when>
									 
								<xsl:otherwise>
								<rh:wind_dir lat="{time[5]/windDirection/@code}"><xsl:copy-of select="time[5]/windDirection/@code" /></rh:wind_dir>
								</xsl:otherwise>
							  </xsl:choose>
							  </xsl:variable>
						   
					   
					   <rh:wind_dir lat="{time[5]/windDirection/@code}"><xsl:value-of select="$wind_cyril_Plato" /></rh:wind_dir>
                       <rh:wind_deg><xsl:value-of select="time[5]/windDirection/@deg" /></rh:wind_deg>					   
					   <rh:wind_speed><xsl:value-of select="time[5]/windSpeed/@mps" /></rh:wind_speed>
					   <rh:comment></rh:comment>	
					 
			         </rh:period>
			
			 </rh:station>			               					      
			</xsl:for-each> 
		</rh:forecast>
	  </xsl:template>
			
			
</xsl:stylesheet>