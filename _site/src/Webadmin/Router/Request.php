<?php
/**
 * Request
 *
 * Processes URI requests
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Webadmin\Router;


class Request
{
	public $language;
	public $requestType;
	public $controller;
	public $id;
	public $query;
	
	protected $webadmin = 'webadmin2';
	protected $uriArray = array();

	
	public function __construct(array $server)
	{
		$uri = $server['REQUEST_URI'];
		$this->uriArray = $this->createUriArray($uri);
	}
	
	
	/**
	 *  @brief Gests URI and returns array with pieces
	 *  
	 *  @return Array of URI pieces
	 *  
	 */
	protected function createUriArray($uri)
	{
		$parsedUri =  parse_url($uri);
		
		$fullArray = explode('/', trim($parsedUri['path'], '/'));
		$webadminIndex = array_search($this->webadmin, $fullArray);
		$processedArray = array_slice($fullArray, $webadminIndex+1);

		// Default, no controller
		if (empty($processedArray[1])){
			$this->controller = DEFAULT_CONTROLLER;
			return;
		}
		$querystring = (!empty($parsedUri['query'])) ? $parsedUri['query'] : false;
		
		$this->language 	= $processedArray[0];
		$this->requestType 	= $processedArray[1];
		$this->controller 	= $processedArray[2];
		$this->clave 		= (isset($processedArray[3])) ? $processedArray[3] : null;
		$this->id 			= (isset($processedArray[4])) ? $processedArray[4] : null; 
		parse_str($querystring, $this->query);

		define('ADMIN_LANGUAGE', $this->language);
	}
	
	
	/**
	 *  Getters & setters
	 */
	public function getWebadmin()
	{
		return $this->webadmin;
	}
	
	public function setWebadmin($webadmin)
	{
		$this->webadmin = $webadmin;
	}
	
	public function getUriArray()
	{
		return $this->uriArray;
	}

}

// End file