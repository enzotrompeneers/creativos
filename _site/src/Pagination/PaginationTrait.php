<?php

namespace Brunelencantado\Pagination;

trait PaginationTrait
{

    protected $pagination;
    protected $paginationOn = true;

    public $querystring;
    
    
	/**
	 * @brief Prepares pagination object
     * 
     * @param Array $query
	 * @return Pagination
	 */
	protected function setupPagination($data)
	{

        $sql = $data;
		$nRows = ($sql) ? $sql->num_rows : 0;
        $this->rows = $nRows;
        
        // Set up querystring
        $this->querystring = $this->getQuerystring();

		$iPage = (isset($_GET['page'])) ? intval($_GET['page']) : 1;
		$location = $this->menu->getUrl($this->genericListPage)  . $this->querystring . '&page';
		$oPagination = new Pagination($this->limitPerPage, $nRows, $iPage, $location);
		
		// Items for paging
		$this->iLimitStart 	= $oPagination->prePagination();
		$this->max = ($this->rows > $this->limitPerPage) ? $this->iLimitStart + $this->limitPerPage : $this->rows;
        
        // Set up querystring

		return $oPagination;
    }

	
	/**
	 * Returns pagination HTML
	 *  
	 * @return String
	 */
	public function pagination()
	{
		return $this->pagination->pagination();
	}
	
	/**
	 * @brief Sets pagination to true or false
	 *
	 * @param Boolean $boolean
	 * @return Void
	 */
	public function setPagination($boolean)
	{

		$this->paginationOn = $boolean;

	}

	/**
	 * @brief Is the pagination on?
	 *
	 * @return Boolean
	 */
	public function getPagination()
	{

		return $this->paginationOn;

	}
    
	/**
	 * @brief Gets querystring for pagination
	 *  
	 * @return String
	 */
	protected function getQuerystring()
	{
		// Returns the full querystring of the current URL
		$querystring = "?";
		foreach($_GET as $k=>$v){ 
			if ($k != 'page' && $k != 'submit' && $k != 'slug' && $k != 'idioma' && !is_array($v)){
				
				$querystring = $querystring . $k . '=' . $v . '&';
			}
		} 
		return $querystring;
	}

}