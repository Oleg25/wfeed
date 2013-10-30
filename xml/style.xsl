<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:rh="uri:rosa_khutor_resort">
<xsl:output method="xml" encoding="utf-8" indent="no" omit-xml-declaration="no"/>
<!--<xsl:strip-space elements="*"/>-->

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

			<sky id="{sky/@id}"><xsl:value-of select="sky"/></sky>
				<xsl:choose>
						<xsl:when test="temp = 'Ошибка сенсора'"><temp_now>Ошибка сенсора (Sensor error)</temp_now></xsl:when>
						<xsl:when test="temp = 'Нет сенсора'"><temp_now>Нет сенсора (Sensor not installed)</temp_now></xsl:when>
						<xsl:otherwise><temp_now><xsl:value-of select="temp"/></temp_now></xsl:otherwise>
			   	</xsl:choose>

				       <temp_afternoon><xsl:value-of select="temp_aft"/></temp_afternoon>
                       <wind_dir lat="{wind_dir/@lat}"><xsl:value-of select="wind_dir" /></wind_dir>
					   <wind_deg><xsl:value-of select="wind_deg" /></wind_deg>

					 <xsl:choose>
						<xsl:when test="wind_speed = 'Ошибка сенсора'"><wind_speed>Ошибка сенсора (Sensor error)</wind_speed></xsl:when>
						<xsl:when test="wind_speed = 'Нет сенсора'"><wind_speed>Нет сенсора (Sensor not installed)</wind_speed></xsl:when>
						<xsl:otherwise><wind_speed><xsl:value-of select="wind_speed"/></wind_speed></xsl:otherwise>
			         </xsl:choose>

				<xsl:choose>
						<xsl:when test="snow = 'Ошибка сенсора'"><snow>Ошибка сенсора (Sensor error)</snow></xsl:when>
						<xsl:when test="snow = 'Нет сенсора'"><snow>Нет сенсора (Sensor not installed)</snow></xsl:when>
						<xsl:otherwise><snow><xsl:value-of select="snow"/></snow> </xsl:otherwise>
			   	</xsl:choose>
               			<comment><xsl:value-of select="comment"/></comment>
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
				  

			         <period for="D1">

					   <sky id="{time[1]/symbol/@id}"><xsl:value-of select="time[1]/symbol/@name" /></sky>
					   <temp_mon><xsl:value-of select="time[1]/temperature/@value" /></temp_mon>
					   <temp_afternoon><xsl:value-of select="time[2]/temperature/@value" /></temp_afternoon>

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
								   <wind_dir lat="{time[1]/windDirection/@code}"><xsl:copy-of select="time[1]/windDirection/@code" /></wind_dir>
								 </xsl:otherwise>
							   </xsl:choose>
							 </xsl:variable>


					   <wind_dir lat="{time[1]/windDirection/@code}"><xsl:value-of select="$wind_cyril_Pik" /></wind_dir>
                       <wind_deg><xsl:value-of select="time[1]/windDirection/@deg" /></wind_deg>
					   <wind_speed><xsl:value-of select="time[1]/windSpeed/@mps" /></wind_speed>
					   <comment></comment>

			         </period>


			        <period for="D2">

					 <sky><xsl:value-of select="time[5]/symbol/@name" /></sky>
					   <temp_mon><xsl:value-of select="time[5]/temperature/@value" /></temp_mon>
					   <temp_afternoon><xsl:value-of select="time[6]/temperature/@value" /></temp_afternoon>

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
								<wind_dir lat="{time[5]/windDirection/@code}"><xsl:copy-of select="time[5]/windDirection/@code" /></wind_dir>
								</xsl:otherwise>
							  </xsl:choose>
							  </xsl:variable>


					   <wind_dir lat="{time[5]/windDirection/@code}"><xsl:value-of select="$wind_cyril_Plato" /></wind_dir>
                       <wind_deg><xsl:value-of select="time[5]/windDirection/@deg" /></wind_deg>
					   <wind_speed><xsl:value-of select="time[5]/windSpeed/@mps" /></wind_speed>
					   <comment></comment>

			         </period>

			 </rh:station>
			</xsl:for-each>
		</rh:forecast>
	  </xsl:template>

</xsl:stylesheet>