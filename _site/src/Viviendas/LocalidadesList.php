<?php
/**
 * Property list class
 *
 * Miralbo
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Viviendas;

class LocalidadesList
{
	
	protected $query 			= null;
	protected $filters 			= array();
	protected $table 			= 'localidades';
	protected $db;

	public function __construct($db)
	{
		$this->db = $db;
	}
	
	/**
	 *  Gets list of viviendas
	 *  
	 *  @return Array
	 */
	public function getList($order = false, $limit = false) 
	{
		global $base_site, $pagina;
		
		
		$sql = $this->getData($order, $limit);
	
		
		return $sql;
		
	}
	
	/**
	 *  @brief Get results form database
	 *  
	 *  @param [in] $limit Limit
	 *  @return Array
	 *  
	 */
	protected function getData($order = false, $limit = false)
	{
		$query = "SELECT id, nombre FROM ".XNAME."_{$this->table} WHERE 1=1";
			
		// Filters
		if (!empty($this->filters)){
			foreach ($this->filters as $filter){
				$query .= " AND ({$filter})";
			}
		}
		
		// printout($this->filters);
		
		
		// Order
		if ($order) {
			$query .= " ORDER BY {$order}";
		}
		
		// Limit
		if ($limit) {
			$query .= " LIMIT {$limit}";
		}
		
		$this->query = $query;
		
		
		return $this->db->dataset($query);
		
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
	 *  Removes all filters
	 *  
	 *  
	 *  @return void
	 */
	public function removeFilters()
	{
	
		$this->filters = [];
	
	}


	
    /**
     * Sanitizes whole array of data for SQLconsumption
     * 
     * @param <type> $data array 
     * 
     * @return <type> array
     */
	
	public function sanitize(array $data)
	{
		
		$output = [];
		
		foreach ($data as $k => $v) {
			
			if ($v) $output[$k] = filter_var($v, FILTER_SANITIZE_STRING);
			
		}
		
		return $output;
		
	}
	
	/**
	 *  Returns pagination HTML
	 *  
	 *  @return void
	 */
	public function pagination()
	{
		return $this->pagination->pagination();
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