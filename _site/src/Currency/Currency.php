<?php
/**
 * Currency converter
 * 
 * Copyright (C)2016 Daniel Beard daniel@brunel-encantado.com
 *
 */
 
namespace Brunelencantado\Currency;
 
class Currency 
{
	
	protected $currency;
	protected $file = 'currency.json';
	protected $url = 'http://api.fixer.io/latest';
	protected $data;
	
	public function __construct($currency = 'GDP')
	{
		$this->currency = $currency;
	}
	
	/**
	* @brief Conversion method
	*  
	* @param Integer $price price to be converted
	* @return Integer Converted price
	*/
	public function convert($price = 0)
	{
		$file = dirname(__FILE__).'/'.$this->file;
		$jsonFile = json_decode(file_get_contents($file));
		$jsonDate = $jsonFile->dateTime;
		$nowDate = date('Y-m-d H:i:s');
		$diffDates = strtotime($nowDate)-strtotime($jsonDate);

		if ($diffDates>43200) {
			$this->updateFile();
		}
		
		$conversionRate = $jsonFile->{$this->currency};
		return $price * $conversionRate;
	}
	
	/**
	 * @brief Sets the API url
	 *  
	 * @param Integer $url Description for $url
	 * @return Void
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}
	

	/**
	 *  @brief Update the json file with latest rate
	 *  
	 */
	protected function updateFile()
	{
		$data = array();
		$rawJSON 				= $this->downloadPage($this->url);
		$convertedJSON 			= json_decode($rawJSON);
		$data[$this->currency] 	= $convertedJSON->rates->{$this->currency};
		$data['dateTime'] 		= date('Y-m-d H:i:s');
		$json = json_encode($data);
		$file = dirname(__FILE__).'/'.$this->file;
		$jsonFile = fopen($file, 'w');
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


// End of file