<?php

	/*==================================================================*\
	######################################################################
	#                                                                    #
	# Copyright 2009 Dynno.net .  All Rights Reserved.                   #
	#                                                                    #
	# This file may not be redistributed in whole or part.               #
	# 							                                         #
	# Developed by: $ID: 1 $UNI: Imad Jomaa                              #
	# ----------------------- THIS FILE PREFORMS ----------------------- #
	#                                                                    #
	# Pagination                                                         #
	######################################################################
	\*==================================================================*/

namespace Brunelencantado\Pagination;

Class Pagination {
   

  protected $limit;
  protected $rows;
  protected $currentPage;
  protected $location;
  protected $total;
  
  function __construct($limit, $rows, $currentPage, $location)
  {

    $this->limit        = intval($limit);
    $this->rows         = intval($rows);
    $this->currentPage = intval($currentPage);
    $this->location     = strip_tags($location);
    
  }

  /******
   * Prepares the pagination
   * @return 
   */
  
  public function prePagination()
  {
    global $next, $previous, $total;
  
    $this->total = ceil($this->rows/$this->limit); 

    $start = ($this->currentPage - 1) * $this->limit;
    if($this->currentPage <= 1) $start = 0;
    if($this->currentPage > $this->total) $start = 0;

    

    return $start;

  }
  
  /******
   * Handles the pagination
   * @return 
   */
  
  public function pagination()
  {
    global $next, $previous, $total;
    
    $location = $this->location;
    $total = $this->total;
    $page = $this->currentPage;

    //for templates sake
    $currentPage = intval($this->currentPage);

    //sum up the way the pagination shall work
    if($this->currentPage < 1 && $total > $this->currentPage)
    {
      $previous = 0;
      $next     = $this->currentPage + 1;
    }

    if($this->currentPage >= 1 && $this->total > $this->currentPage)
    {
      $previous = $this->currentPage - 1;
      $next     = $this->currentPage + 1;
    }

    if($this->currentPage >= 1 && $total == $this->currentPage)
    {
      $previous = $this->currentPage - 1;
      $next     = 0;
    }
    
    //check if the template file exists


    require_once dirname(__FILE__) . '/paginationView.php';

  }

}
