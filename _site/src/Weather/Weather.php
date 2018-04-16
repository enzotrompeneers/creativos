<?php
/**
 * Weather class
 *
 * Gets weather in JSON format from openweathermap.org
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 */

namespace Brunelencantado\Weather;


class Weather
{
	protected $location;
	protected $localFile;
	protected $jsonUrl;
	protected $apiKey = 'e9eaa2dbf4a49e429a89501c3bc3a527';
	protected $url = 'http://api.openweathermap.org/data/2.5/weather?APPID=';
	
	
	public function __construct($location = 'Torrevieja')
	{
		$this->location = $location;
		$this->jsonUrl = $this->url . $this->apiKey . '&q=' . $this->location; // Create API url from parameters
		$this->localFile = dirname(__FILE__) . '/' . $this->location . '.json';
	}
	
	/**
	 *  @brief checks for latest weather and returns  appropiate symbol
	 *  
	 *  @return Return_Description
	 *  
	 */
	public function showWeather()
	{
		// Get or create local json file
		if (!file_exists($this->localFile)){
			 $newFile = fopen($this->localFile, "w");
			 $this->updateFile();
		}
		
		// Check to see if one hour has passed since last update, if so update file form remote API
		$jsonFile = json_decode(file_get_contents($this->localFile));
		$jsonDate = $jsonFile->dateTime;
		$nowDate = date('Y-m-d H:i:s');
		$diffDates = strtotime($nowDate)-strtotime($jsonDate);
		
		if ($diffDates>3600) {
			$this->updateFile();
			$jsonFile = json_decode(file_get_contents($this->localFile));
		}		
		
		// Get weather data and add to anonymous object
		$weatherJson 			= json_decode($jsonFile->weather);
		$weather 				= new \stdClass();
		$weather->id 			= $weatherJson->weather[0]->id;
		$weather->temperature 	= ceil($weatherJson->main->temp - 273.15);
		$weather->symbol 		= $this->getSymbol($weather->id);
		

		
	}
	
	/**
	 *  @brief Gets CSS class for id code
	 *  
	 *  @param [in] $id weather code from Open Weather Map API
	 *  @return CSS code
	 *  
	 *  @details see http://openweathermap.org/weather-conditions for id codes
	 *  		 see http://forecastfont.iconvau.lt/ for icons
	 */
	protected function getSymbol($id)
	{
		
	}
	
	// Get latest API data and update timestamp
	protected function updateFile()
	{
		$data = array();
		$rawJSON 				= $this->downloadPage($this->jsonUrl);
		$convertedJSON 			= json_decode($rawJSON);
		$data['weather'] 		= $rawJSON ;
		$data['dateTime'] 		= date('Y-m-d H:i:s');
		$json = json_encode($data);
		$jsonFile = fopen($this->localFile, 'w');
		fwrite($jsonFile, $json);
	}

	// descarga desde una url
	protected function downloadPage($path){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$path);
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		$retValue = curl_exec($ch);                      
		curl_close($ch);
		return $retValue;
	}
	
}




// End file