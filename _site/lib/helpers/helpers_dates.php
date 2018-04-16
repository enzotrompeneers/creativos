<?php
// Date helper functions

 // Creates an array from date range.
function createDateRangeArray($strDateFrom,$strDateTo) {
  $aryRange=array();
  $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
  $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));
  if ($iDateTo>=$iDateFrom) {
    array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
    while ($iDateFrom<$iDateTo) {
      $iDateFrom+=86400; // add 24 hours
      array_push($aryRange,date('Y-m-d',$iDateFrom));
    }
  }
  return $aryRange;
}


// Converts month numbers to abreviations. Add new languages as needed!
function fecha_trad($mes) {
 global $language;
 $mes = ltrim($mes, '0');
 $mes_es=array('','ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC');
 $mes_en=array('','JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC');
 if ($language=='es') { return $mes_es[$mes]; }
 if ($language=='en') { return $mes_en[$mes]; }
}

//Converts dates to timestamp/ISO format (2009-10-29)
function convertFecha($fecha){
	//Converting date from 10/10/2009 format to 10-10-2009 format, to save as timestamp
	//format: 2009-10-29
	$fecha = split("/", $fecha);
	$fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
	return ($fecha);			
}

// Checks if supplied  is between dates
function isDateBetween($dt_start, $dt_check, $dt_end){
  if(strtotime($dt_check) > strtotime($dt_start) && strtotime($dt_check) < strtotime($dt_end)) {
    return true;
  }
  return false;
} 

// End file