<?php
/**
 * xml.class.php
 * Copyright (C)2015  Daniel Beard <daniel@brunel-encantado.com> 
 * 
 * Database helper static functions
 * 
 */






class DatabaseHelpers
{
	
	 /**
     * 
     * @param array $insert
     * @param type $table
     * @return type
     */
    public static function InsertQuery(array $insert, $table)
    {
	$keys	= array_keys($insert);
	$values	= array_values($insert);
	$ultimo = array_pop($values);
	$query  = 'INSERT INTO ' . $table . '(' . implode(', ' , $keys) . ') VALUES (';
	foreach ($values as $value) {
		$query .= '\'' . $value . '\', ';

	}
	
	if (is_numeric($ultimo)) {
	    $query .= $ultimo;
	}
	else {
	    $query .= '\'' . $ultimo . '\'';
	}
	
	return $query . ');';
    }
	
    /**
     * 
     * @param array $insert
     * @param type $table
     */
    public static function UpdateQuery(array $update, $table, array $where)
    {
	$query  = 'UPDATE ' . $table . ' SET ';
	$keys	= array_keys($update);
	$values	= array_values($update);	
	$ultimo = array(array_pop($keys), array_pop($values));
	$count  = count($keys);
	
	for ($i=0; $i < $count; $i++) {
	    $query .= $keys[$i] . '=\'' . $values[$i] . '\', ';
	}
	$query .= $ultimo[0] . '=\'' . $ultimo[1] . '\' ';
	
	if ($where) {
	    $query .= ' WHERE 1=1 ';
	    foreach ($where as $key => $value) {
		$query .= 'AND ' . $key . ' = \'' . $value . '\' ';
	    }
	}
	return $query;
    }
}





// End of file