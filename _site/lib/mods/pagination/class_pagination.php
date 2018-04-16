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

//lets first check if IN_PAGI is defined
if (!defined("IN_PAGI")) { die(header("No Direct Access Allowed")); }
$pagination_inc = TRUE;

Class Pagination {
   
  /******
   * Constructs the class
   * @return 
   * @param object $limit
   * @param object $rows
   * @param object $current_page
   * @param object $location
   */
  
  function __construct($limit, $rows, $current_page, $location)
  {
    $this->limit        = intval($limit);
    $this->rows         = intval($rows);
    $this->current_page = intval($current_page);
    $this->location     = $location;
  }

  /******
   * Prepares the pagination
   * @return 
   */
  
  public function prePagination()
  {
    global $next, $previous, $total;
  
    $this->location     = strip_tags($this->location);
    $this->limit        = intval($this->limit);
    $this->current_page = intval($this->current_page);
    $this->rows         = intval($this->rows);
  
    $total = ceil($this->rows/$this->limit); 
  
    if($this->current_page <= 1)
    {
      $start = 0;
    }
  
    elseif($this->current_page > $total)
    {
      $start = 0;
    }
   
    else
    {
      $start = ($this->current_page - 1) * $this->limit;
    }
  
    return $start;
  }
  
  /******
   * Handles the pagination
   * @return 
   */
  
  public function pagination()
  {
    global $next, $previous, $total, $location, $current_page;
    
    $location = $this->location;
    
    //for templates sake
    $current_page = intval($this->current_page);
    
    //sum up the way the pagination shall work
    if($this->current_page < 1 && $total > $this->current_page)
    {
      $previous = 0;
      $next     = $this->current_page + 1;
    }

    if($this->current_page >= 1 && $total > $this->current_page)
    {
      $previous = $this->current_page - 1;
      $next     = $this->current_page + 1;
    }

    if($this->current_page >= 1 && $total == $this->current_page)
    {
      $previous = $this->current_page - 1;
      $next     = 0;
    }
    
    //check if the template file exists



        @include("pagination.php");

  }

}
?>