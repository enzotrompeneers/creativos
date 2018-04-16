<?php
/**
 * Products class
 *
 * First set up for Simplistic Data Solutions
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 */

namespace Brunelencantado\Products;

use Brunelencantado\Database\MySqliDatabase;

class Product
{
	use Currency;
	
	protected $id 			= null;
	protected $table 		= 'productos';
	protected $connection 	= null;
	protected $currency 	= 'dolares';
	
	
	public function __construct($connection, $id = null, $template, $currency)
	{
		$this->connection 	= $connection;
		$this->id 			= $id;
		$this->template 	= $template;
		$this->currency 	= $currency;

	}
	
	/**
	 *  @brief Retrieve and return product data
	 *  
	 *  @return array with product data
	 *  
	 *  @param $id
	 */
	public function getProductData()
	{
		global $language, $xname;
		
		$query = "
					SELECT p.nombre_{$language} AS titulo, c.nombre_{$language} AS categoria,
					p.descr_{$language} AS descripcion, p.*
					FROM {$xname}_productos p
					JOIN {$xname}_categorias c ON p.categoria_id = c.id
					WHERE p.id = {$this->id}
		";
		$sql = $this->connection->query($query);
		if (is_object($sql)) {
			$output = $sql->fetch_array(MYSQLI_ASSOC);
			return $output;
		} else {
			return false;
		}

	}
	/**
	 *  @brief Get all models form a prodcut
	 *  
	 */
	public function getModels()
	{
		global $language, $xname;
		
		$query = "
					SELECT id,  precio, m.nombre_{$language} AS titulo, 
					m.descr_{$language} AS descripcion, m.orden AS orden
					FROM {$xname}_modelos m
					WHERE producto_id = {$this->id}
					ORDER BY m.orden
				";
				
		$sql = $this->connection->query($query);
		$output = MySqliDatabase::obj2Array($sql);
		return $output;
	}
	
	/**
	 *  @brief Get options for a product, depending on prosuct type
	 *  
	 *  @param [in] $optionType
	 *  
	 *  @return List of options for that type
	 */	
	public function getOptions($optionType)
	{
		global $xname, $language;
		$query = "
					SELECT id, precio, o.nombre_{$language} AS titulo, o.descr_{$language} AS descripcion
					FROM {$xname}_opciones o
					WHERE producto_id = {$this->id}
					AND opcion_tipo = '{$optionType}'
				";
		
		$sql = $this->connection->dataset($query);
		$output = $sql;
		return $output;		
	}	
	
	/**
	 *  @brief Get disks compatible with this product
	 *  
	 *  @return List of disks for that model/series
	 */	
	public function getDisks()
	{
		global $xname, $language;
		$query = "
					SELECT DISTINCT(id), precio, 
					d.nombre_{$language} AS titulo, d.descr_{$language} AS descripcion
					FROM {$xname}_has_producto h
					JOIN {$xname}_discos d ON h.producto_id = {$this->id}
					WHERE h.producto_id = {$this->id}
				";
		
		$sql = $this->connection->query($query);
		if (is_object($sql)){
			$output = MySqliDatabase::obj2Array($sql);
			return $output;				
		} else {
			return false;
		}
	
	}
	
	/**
	 *  @brief Show list for product (models, options, etc...)
	 *  
	 *  @param [in] $array Array of features/options/models, etc...
	 *  @param [in] $clave 

	 *  
	 *  @details Details
	 */
	public function getList(array $array, $clave, $tipo = 'radio')
	{
		if (empty($array)) return false;

		$active = ($clave=='model')?'active':'';
		
		$data['currencySymbol'] = ($this->currency=='dolares')?'$':'&euro;';
		$data['object']			= $this;
		
		
		$output 		= '<div class="option-content '.$active.'">
								<ul>';

		$n = 1;

		foreach ($array as $k=>$v) {
			
			$data['id']			= $v['id'];
			$data['clave']		= $clave;
			$data['titulo']		= $v['titulo'];
			$data['precio']		= $this->currencyConvert($v['precio'], $this->currency);
			$data['descripcion']= $v['descripcion'];
			$data['checked']	= ($n==1 && $tipo=='radio')?'checked="checked"':'';
			
			$output .= $this->template->render('input_'.$tipo, $data);

			$n++;
		}
		$output 	.= '</ul>
						</div>';
		
		return $output;
	}
	
	public function showFirstModel()
	{
		
	}
	

	
	public function getId($id)
	{
		return $this->id;
	}
	
	public function setId(int $id)
	{
		 $this->id = $id;
	}
	

	/**
	 *  Changes the product table
	 *  
	 *  @return void
	 */
	public function setTable($tableName)
	{
		$this->table = $tableName;
	}
	

	

	
}


// End of file