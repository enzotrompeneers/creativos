<?php
/**
 * Products trait
 *
 * For currency conversion functionality
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 */
 
 namespace Brunelencantado\Products;
 
 trait Currency
 {
	 protected $dollar2Euro  = 0;
	 
	
	 /**
	 *  Formats price depending on language
	 */	
	public function precio($precio, $currency='dolares')
	{
		global $language;
		$precio = $this->currencyConvert($precio, $currency);
		switch ($language) {
			case 'en':
				return number_format($precio, 0, '.', ',');
				break;
			case 'es':
				return number_format($precio, 0, ',', '.');
				break;
			default:
				return number_format($precio, 0, ',', '.'); 
				break;
		}
	}
	
	protected  function currencyConvert($precio, $currency='dolares')
	{
		if ($currency=='euros'){
			$conversionRate = $this->getConfig('dollar2euro');
			$precio = $precio*$conversionRate;
		}
		return $precio;
	}
	
	 /**
	 *  Gets config result
	 *  
	 *  @return exchange rate 1$ = x€
	 */
	protected function getConfig($clave)
	{
		global $xname;
		$query = "SELECT valor FROM {$xname}_config WHERE clave = '{$clave}'";
		$sql = $this->connection->record($query);
		$output = $sql['valor'];
		return $output;

	}	
 }