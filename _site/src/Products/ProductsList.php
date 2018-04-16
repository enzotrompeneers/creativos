<?php
/**
 * Products list class
 *
 * First set up for Simplistic Data Solutions
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 */

namespace Brunelencantado\Products;


class ProductsList
{
	protected $query 		= null;
	protected $filters 		= array();
	protected $table 		= 'productos';
	protected $connection 	= null;
	
	public function __construct($connection)
	{
		$this->connection = $connection;
	}
	
	/**
	 *  Summary
	 *  
	 *  @return list of products
	 */
	public function getProducts() 
	{
		global $xname, $language, $base_site;
		
		$query = "
					SELECT p.id AS pid, p.nombre_{$language} AS titulo, c.nombre_{$language} AS categoria
					FROM {$xname}_{$this->table} p
					JOIN {$xname}_categorias c ON p.categoria_id = c.id
					WHERE 1 = 1
		";
		if (!empty($this->filters)){
			foreach ($this->filters as $filter){
				$query .= " AND {$filter} ";
			}
		}
		$sql = $this->connection->query($query);
		
		$output = array();
		$n = 0;
		foreach ($sql as $k=>$v){
			$output[$n]['id'] 			= $v['pid'];
			$output[$n]['titulo'] 		= $v['titulo'];
			$output[$n]['categoria'] 	= $v['categoria'];
			$output[$n]['link'] 		= $base_site.$language.'/'.slugged('productos').'/'.slug($output[$n]['categoria']).'/'.slug($output[$n]['titulo']).'-'.$v['pid'].'.html';
			$output[$n]['img'] 			= first_image($this->table,$output[$n]['id']);
			$n++;
		};
		return $output;
		
	}

	 /**
	 *  Adds filter to query
	 *  
	 *  @param [in] $filter filter to add to query
	 *  
	 *  @return void
	 */
	public function addFilter($filter)
	{
		$this->filters[] = $filter;
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