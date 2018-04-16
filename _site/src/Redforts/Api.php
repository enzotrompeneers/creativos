<?php
/**
 * Interfaces with the Redforts API
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Redforts;

class Api
{
	
	protected $apiUrl;


	
	/**
	 * Create a new Instance
	 */
	public function __construct($apiUrl)
	{

		$this->apiUrl = $apiUrl;
	
	}
	
	public function inventory()
	{
		
		$url = $this->apiUrl . 'inventory';
		
		$response = $this->postUrl($url);
		
		return $response;
		
	}
	
	public function availability($arrivalDate, $departureDate, $code)
	{
		
		$url = $this->apiUrl . 'availability';
		
		$data = [];
		$data['api_version'] = 2;
		$data['arrival'] = $arrivalDate;
		$data['departure'] = $departureDate;
		$data['promo'] = $code;
		$data['lang'] = LANGUAGE;
		$response = $this->postUrl($url, $data);
		
		return $response;
		
	}
	
	public function makeReservation($payload)
	{
		$url = $this->apiUrl . 'reservation';
		$response = $this->postUrl($url, $payload);
		
		return $response;
		
	}
	
	protected function postUrl($url, $postData = null)
	{
		
		$options = array(
			'http' => array(
			'header'  => "Content-Type: application/json",
			'method'  => 'POST',
			'content' => json_encode($postData)
			)
		);
		
		
		$context  = stream_context_create($options);
		$output = file_get_contents($url, false, $context);
		
		return $output;
		
	}


	protected function dateDifference($date1, $date2)
	{

		$oDate1 = DateTime::createFromFormat('d/m/Y', $date1);
		$oDate2 = DateTime::createFromFormat('d/m/Y', $date2);

		$interval = $oDate1->diff($oDate2);

		return $interval;

	}
}


// End of file