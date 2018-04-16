<?php
/**
 * Products class
 *
 * First set up for Simplistic Data Solutions
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 */

namespace Brunelencantado\Products;

use Brunelencantado\Products\Product;
use Brunelencantado\Database\MySqliDatabase;

class Quote
{
	use Currency;
	
	protected $connection 	= null;
	protected $template 	= null;
	protected $currency 	= 'dolares';
	protected $id 			= null;
	protected $model 		= null;
	protected $options 		= array();
	protected $disks 		= array();
	
	protected $quoteData 	= array();
	protected $totalPrice 	= 0;
	protected $aQuote		= array();
	
	
	
	public function __construct($connection, $id, $template, $currency='dolares')
	{
		$this->connection 	= $connection;
		$this->template 	= $template;
		$this->currency 	= $currency;
		$this->id 			= filter_var($id, FILTER_SANITIZE_STRING);
		$this->dollar2Euro 	= $this->getConfig('dollar2Euro'); // Get exchange rate
	}

	/**
	 *  @brief Set model
	 *  
	 */
	public function setModel($model)
	{
		$this->model = filter_var($model, FILTER_SANITIZE_STRING);
	}
	
	/**
	 *  @brief Add options
	 *  
	 *  @param [in] $options array of options

	 */
	public function addOptions(array $options)
	{
		foreach ($options as $k=>$v) {
			$this->options[] = filter_var($v, FILTER_SANITIZE_STRING);
		}
	}
	
	
	public function addDisks(array $disks)
	{
		foreach ($disks as $k=>$v) {
			if ($v>0) $this->disks[$k] = filter_var($v, FILTER_SANITIZE_STRING);
		}

	}	

	
	public function getAjaxQuote()
	{
		$data['currency'] 		= $this->currency;
		$data['currencySymbol'] = ($this->currency=='dolares')?'$':'&euro;';
		$data['aProduct'] 		= $this->getProductDetails();
		$data['aModel'] 		= $this->getModelDetails();
		$data['aOptions'] 		= $this->getOptionDetails();
		$data['aDisks'] 		= $this->getDiskDetails();
		$data['totalPrice']		= $this->totalPrice;
		$data['object']			= $this;
		
		$output = $this->template->render('quote', $data);
		
		
		return $output;
		
	}
	
	public function calculateQuote ()
	{

		$aQuote = array();
		$aQuote['product'] 		= $this->id;
		$aQuote['model'] 		= $this->model;
		$aQuote['total_price']	= $this->currencyConvert($this->getPrice($aQuote['model'],'modelos'), $this->currency);;
		
		// Options prices
		$aQuote['options'] 	= array();	
		foreach ($this->options as $o){
			$aQuote['options'][] = $o;
			$aQuote['total_price'] += $this->currencyConvert($this->getPrice($o,'opciones'), $this->currency);
		}
		
		// Disk prices
		foreach ($this->disks as $k=>$v){
			$aQuote['total_price'] += $this->currencyConvert($this->getPrice($k,'discos') * $v, $this->currency);
		}
		$this->totalPrice = $aQuote['total_price'];
		$this->aQuote = $aQuote;

	}
	
	public function quoteFromJson($json)
	{
		$aQuote = json_decode($json);

		$this->model = $aQuote->model;
		$this->options = $aQuote->options;
		$this->aQuote = $aQuote;
	}
	
	public function checkJsonPrice($json)
	{
		$oQuote = json_decode($json);
		$totalPrice	= $this->getPrice($oQuote->model,'modelos');
		foreach ($oQuote->options as $o){
			$totalPrice	+= $this->getPrice($o,'opciones');
		}
		return $totalPrice;
	}
	
	public function getJsonQuote()
	{
		$output = json_encode($this->aQuote);
		
		return  htmlspecialchars($output);
	}
	
	public function getArrayQuote()
	{
		return $this->aQuote;
	}
	
	protected function getProductDetails()
	{
		global $xname, $language;

		$product = new product($this->connection, $this->id, $this->template, $this->currency);
		$aProduct = $product->getProductData();
		return $aProduct;
	}
	
	protected function getPrice($id, $folder)
	{
		global $xname, $language;
		$id = filter_var($id, FILTER_SANITIZE_STRING);
		$query = "SELECT  precio FROM {$xname}_{$folder} WHERE id = {$id}";
		$sql =$this->connection->query($query);

		if ($sql){
			$output =  mysqli_fetch_assoc($sql);
			
			$precio = $this->currencyConvert($output['precio']);
			return $precio;	
		} else {
			return false;
		}

	}
	
	protected function getModelDetails()
	{
		global $xname, $language;
		$modelQuery = "SELECT nombre_{$language} AS titulo,  precio FROM {$xname}_modelos WHERE id = {$this->model}";
		$modelSql = $this->connection->query($modelQuery);
		if (!empty($modelSql)) {
			
			return mysqli_fetch_assoc($modelSql);
		}
		
	}
	
	protected function getOptionDetails()
	{
		global $xname, $language;

		$options = $this->options;
		$aOptions = array();
		
		if (!empty($options)) {
			foreach ($options as $o){
				$optionQuery = "SELECT id, nombre_{$language} AS titulo,  precio, opcion_tipo FROM {$xname}_opciones WHERE id = {$o}";
				$aOptions[] = mysqli_fetch_assoc($this->connection->query($optionQuery));
			}
		}
		return $aOptions;
	}
	
	protected function getDiskDetails()
	{
		global $xname, $language;

		$disks = $this->disks;
		$aDisks = array();
		
		if (!empty($disks)) {
			$n = 0;
			foreach ($disks as $k=>$v){
				$diskQuery = "	SELECT id, nombre_{$language} AS titulo, 
								precio 
								FROM {$xname}_discos 
								WHERE id = {$k}
									";
				
				$aDisks[$n] = mysqli_fetch_assoc($this->connection->query($diskQuery));
				$aDisks[$n]['cantidad'] = $v;
				$aDisks[$n]['precio'] 	= $v * $aDisks[$n]['precio'];
				$n++;
			}
		}
		return $aDisks;
	}

	
}


// End of file