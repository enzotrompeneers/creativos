<?php

function getData($folder, $field=null)
{
	global $xname,$language;
	$fieldSelect = ($field)?$field:'nombre_'.$language;
	$query 	= "SELECT id, {$fieldSelect} AS nombre FROM {$xname}_{$folder} ORDER BY {$fieldSelect }";
	$sql 	= dataset($query);
	// printout($query);
	$choose = array('id'=>'','nombre'=>trad('elige')); // Primera opción
	if (!empty($sql)){
		array_unshift($sql,$choose);
		return $sql;
	} else {
		return false;
	}

}

function getRange($min,$max,$step=1) 
{
	$output = array();
	$n = 1;
	foreach (range($min,$max,$step) as $number) {
		$output[$n]['id'] = $number;
		$output[$n]['nombre'] = number_format($number);
		$n++;
	}
	
	$choose = array('id'=>'','nombre'=>trad('elige')); // Primera opción
	array_unshift($output,$choose);
	return $output;
	
}

function getYesNo(){
	$output = array();
	$output[0]['id'] = 0;
	$output[0]['nombre'] = trad('no');
	$output[1]['id'] = 1;
	$output[1]['nombre'] = trad('si');
	return $output;
	
}
// End of file