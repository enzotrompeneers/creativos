<?php
/**
 * Database connect and tools class
 *
 * Connects to database and implements basic functions
 *
 * @author Daniel Beard <daniel@brunel-encantado.com>
 *
 */

namespace Brunelencantado\Database;

use \Brunelencantado\Logger\Logger;

interface DbInterface
{	

	// Constructor, gets connection data and connects
	public function __construct(array $aConnectionData, Logger $log = null);
	
	// Insert query builder
    public function insertQuery(array $insert, $table);
	
	// Update query builder
	public function updateQuery(array $update, $table, array $where);
	
	// Delete query builder
	public function deleteQuery ();
	
	// Basic query | returns true or error
	public function query($query);
	
	// Debug mode | Set to true if we want debug mode
	public function setDebugMode($debug = false);
	
	// Shows the logged details
	public function showLog();
	
	// Returns last inserted db entry
	public function lastId();
	
	// Sanitize variable for query
	public function sanitize($rawString);
	
	// Converts object result to array
	public static function obj2Array($object);
	
	// Returns 1 table row as array
	public function record($query);
	
	// Returns multiple table rows as array
	public function dataset($query);

}


// End of file