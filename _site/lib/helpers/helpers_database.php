<?php
// Database stuff

//conecta la base de datos
function run_query($sql) 
{
	$result = mysql_query($sql) 
		or die ("Fallo en el Query: ".mysql_error());
	return $result;
}

function dataset($str){
    $q = mysql_query($str);
	if (!empty($q)) {
		while($rec	= mysql_fetch_assoc($q))
			$set[] 	= $rec;
		if (!empty($set)){
			return $set;
		 } else {
			return FALSE;
		 }
	 }
}

function record($sql){ 
	$q = mysql_query($sql);
	if (!empty($q)){
		$r = mysql_fetch_assoc($q); 
		mysql_free_result($q);
		return $r;
	} else {
		return false;
	}
	
}

// Function to create INSERT statement
function insertDb ($fields,$table) {
	$query = 'INSERT INTO '.$table.' (';
	foreach ($fields as $k=>$v) {
		$query .= $k.',';
	}
	$query = rtrim($query,',');
	$query .= ') VALUES (';
	foreach ($fields as $k=>$v) {
		$q = '\'';
		$query .= $q.mysql_real_escape_string($v).$q.',';
	}
	$query = rtrim($query,',');
	$query .= ');';
	return $query;
}

// Function to create UPDATE statement
function updateDb ($fields,$table,$id,$idName='id') {
	$query = 'UPDATE '.$table.' ';
	$query .= ' SET ';
	foreach ($fields as $k=>$v) {
		$q = '\'';
		$query .= $k.' = '.$q.mysql_real_escape_string($v).$q.',';
	}
	$query = rtrim($query,',');
	$query .= " WHERE {$idName} = '{$id}'";
	return $query;
}

//Devuelve el maximo valor de un elemento de una tabla determinada
function select_max($mid,$table){
	// Return max value
	$maxID = record("SELECT MAX($mid) AS max FROM ".$table);
	return $maxID['max'];
}

// Tells us if a table has an image table associated
function table_has_images($folder,$type='images'){
	global $xname,$language;
	if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '{$xname}_{$type}_{$folder}'"))==1) {
		return TRUE;
	} else {
		return FALSE;
	}
}

// Gives us the comment for a column
function  get_comment($folder,$column) {
	global $xname,$language;
	$query = 		"	SELECT COLUMN_COMMENT
						FROM INFORMATION_SCHEMA.COLUMNS
						WHERE TABLE_NAME = '{$xname}_{$folder}' 
						AND COLUMN_NAME = '{$column}'
						";
	$sql 		= record($query);
	$comment	= $sql['COLUMN_COMMENT'];
	return $comment;
}

// Check to see if table exists
function tableExists($folder){
	global $xname;
	if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$xname.'_'.$folder."'"))==1) {
		return true;
	}
}

// End file







