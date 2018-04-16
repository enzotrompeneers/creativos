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
	# Pagination Conditionals                                            #
	######################################################################
	\*==================================================================*/


	
//include the necessary language pack
require("assets/language_pack.php");

global $previous, $next, $location, $total, $current_page;

$page = $current_page;


echo "<ul class=\"pagination\" align=\"right\">\n";

if($next < 1)
{
  $next =  "<li class=\"arrow unavailable\"><a href=\"\">&raquo;</a></li>";
}

if($next >= 1)
{
  $next = "<li><a href=\"$location=$next\" title=\"\">&raquo;</a></li>";
}

if($previous < 1)
{
  $previous =  "<li class=\"arrow unavailable\"><a href=\"\">&laquo;</a></li>";
}

if($previous >= 1)
{
  $previous = "<li><a href=\"$location=$previous\" title=\"\">&laquo;</a></li>";
}

echo $previous;

if($total <= 7)
{
  for($counter = 1; $counter <= $total; $counter++)
  {
    if($page == $counter)
    {
      echo "<li class=\"current\"><a href=\"\">$counter</a></li>";
    }

    else
    {
      echo "<li><a href=\"$location=$counter\" title=\"\">$counter</a></li>";  
    }
  }
}

elseif($total > 7 && $page <= 7)
{
  if($page + 3 == $total)
  {
    $cc = $page + 3;
    $extra = false;
  }

  elseif($page + 3 > $total)
  {
    $cc = $page;
    $extra = false;
  }
  
  else
  {
    $cc = $page + 3;
    $extra = true;
  }

  for($counter = 1; $counter <= $cc; $counter++)
  {
    if($page == $counter)
    {
      echo "<li class=\"current\"><a href=\"\">$counter</a></li>";
    }

    else
    {
      echo "<li><a href=\"$location=$counter\" title=\"\">$counter</a></li>";  
    }
  }
  
  if($extra == TRUE)
  {
    echo "<li class=\"unavailable\"><a href=\"\">&hellip;</a></li>";
    echo "<li><a href=\"$location=$total\" title=\"\">$total</a></li>";  
  }
}


elseif($page > 7 && $page < $total - 3)
{

  echo "<li><a href=\"$location=1\" title=\"\">1</a></li>";  
  echo "<li class=\"unavailable\"><a href=\"\">&hellip;</a></li>";

  for($counter = $page - 3; $counter <= $page + 3; $counter++)
  {
    if($page == $counter)
    {
      echo "<li class=\"current\"><a href=\"\">$counter</a></li>";
    }
  
    else
    {
      echo "<li><a href=\"$location=$counter\" title=\"\">$counter</a></li>";  
    }
  }

  echo "<li class=\"unavailable\"><a href=\"\">&hellip;</a></li>";
  echo "<li><a href=\"$location=$total\" title=\"\">$total</a></li>";  

}

elseif($page > 7 && $page + 3 == $total)
{

  echo "<li><a href=\"$location=1\" title=\"\">1</a></li>";  
  echo "<li class=\"unavailable\"><a href=\"\">&hellip;</a></li>";

  for ($counter = $page - 3; $counter <= $page + 3; $counter++)
  {

    if($counter == $page)
    {
      echo "<li class=\"current\"><a href=\"\">$counter</a></li>";
    }
    
    else
    {
      echo "<li><a href=\"$location=$counter\" title=\"\">$counter</a></li>";  
    }    
  }
} 

else
{

  echo "<li><a href=\"$location=1\" title=\"\">1</a></li>";  
  echo "<li class=\"unavailable\"><a href=\"\">&hellip;</a></li>";

  for ($counter = $page - 3; $counter <= $total; $counter++)
  {

    if($counter == $page)
    {
      echo "<li class=\"current\"><a href=\"\">$counter</a></li>";
    }
    
    else
    {
      echo "<li><a href=\"$location=$counter\" title=\"\">$counter</a></li>";  
    }    
  }
} 

echo "$next";

//time to get a jump-to list going

//let's display the select box



echo "</ul>\n";
?>