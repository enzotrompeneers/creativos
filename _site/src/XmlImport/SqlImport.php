<?php
/**
 * xml_import.class.php
 * Copyright (C)2015  Daniel beard <daniel@brunel-encantado.com> 
 * XML import functions // Default is for Kyero format, any other format must create child
 * 
 */

namespace Brunelencantado\XmlImport;

use \Brunelencantado\Logger\Logger;

class SqlImport extends XmlImport
{

	
	public	 $log;
	
	public $xml;
	public $db;
	public $remoteDb;

	public $remoteFolder = '';
	
	
	public function __construct($db)
	{
		
		$this->db = $db;	
		$this->log = $db->log;
		
	}
	
	public function setRemoteDb ($remoteDb)
	{
		
		$this->remoteDb = $remoteDb;
		
	}
	

	/**
	 * Retrieves remote properties and puts into an array
	 */	
	public function getRemoteProperties($table, $limit = null)
	{
		
		$query = "SELECT * FROM {$table}";
		
		if ($limit) {
			$query .= " LIMIT " . pLIMIT;
		}
		
		$sql = $this->remoteDb->dataset($query);
		
		return $sql;
		
	}
	

	/**
	 * Gets array of remote images
	 *
	 * @param [type] $id
	 * @param [type] $table
	 * @param string $idField
	 * @param string $ordenField
	 * @return Array
	 */
	public function getRemoteImages($id, $table, $idField = 'parent_id', $ordenField = 'orden')
	{
		
		$query = "SELECT * FROM {$table} WHERE {$idField} = {$id} ORDER BY {$ordenField}";
		$sql = $this->remoteDb->dataset($query);
		
		$output = [];
		foreach ($sql as $k => $v){

			$output[$k] = $v;
			$output[$k]['url'] = $this->remoteFolder . $v['file_name'];

		}

		return $output;
		
	}

	/**
	 * Inserts image
	 *
	 * @return void
	 */
	public function insertImage($table, $fileName, $parentId, $order, $agent)
	{

		$insert = [];	
		$insert['file_name'] 	= $fileName;
		$insert['parent_id'] 	= $parentId;
		$insert['orden'] 		= $order;
		$insert['agente'] 		= $agent;

		$newId = $this->db->insertQuery($insert, $table);

	}

	public function copyImages(Array $images, $newId)
	{

		foreach($images as $image) {
			
			$destination = '../images/viviendas/' . $newId . '/' ;

			$this->downloadImage($image['url'], $destination, $image['file_name']);

		}

	}
	
    /**
     * Strips tags and converts text blocks to html paragraphs
     * 
     * @param <type> $text string
     * 
     * @return <type> string
     */
	
	public function cleanupText($text) {
		
		$untaggedText = strip_tags($text);
		
		$aText = explode("\n", $untaggedText);
		$output ='';

		for($i=0; $i < count($aText); $i++) {
			
			if(strlen(trim($aText[$i])) > 0) $output.='<p>' .trim($aText[$i]).'</p>';
			
		}
		
		return $output;
	}

	public function setAgent($agent)
	{

		$this->agent = $agent;

	}

}







// End of file