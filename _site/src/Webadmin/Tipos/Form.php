<?php
/**
 * Tipos - General CRUD manager for CMS
 *
 * Processes URI requests
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Webadmin\Tipos;

use Brunelencantado\Webadmin\Tipos\InputType;

class Form
{
	protected $table;
	protected $id;
	
	protected $metaData;
	protected $data;
	
	protected $registry;
	protected $db;
	
	protected $ignores = array('id');
	
	/**
	 *  @brief Constructor, sets registry and db properties
	 *  
	 *  @param [in] $db Database class
	 *  
	 */
	 public function __construct ($table, $id, \Brunelencantado\Registry\Registry $registry)
	 {
		 
		$this->table = $table;
		$this->id = $id;
		 
		$this->registry = $registry;
		$this->db = $this->registry->getObject('db');

		// Get table data & metadata
		$this->metaData = $this->getMetaData();		
		$this->data = $this->getData();

		}	
	 
	 /**
	  *  @brief Gets all table metadata
	  *  
	  *  @return Array
	  *  
	  */
	 public function getFormData($templateHandler)
	 {
	
		$metaData = $this->metaData;
		$formData = array();
		foreach ($metaData as $k => $v){
			
			if (in_array($k, $this->ignores)) continue;
			
			$formData[$k]['field'] 		= $v['Field'];
			$formData[$k]['comment'] 	= $v['Comment'];
			$formData[$k]['dataType'] 	= $v['Type'];
			$formData[$k]['tiposType']	= InputType::getType($formData[$k]);
			$formData[$k]['data'] 		= $this->getFieldData($formData[$k]['field'] , $formData[$k]['tiposType'],  $formData[$k]['comment']);
			
			$formData[$k]['rendered']	= $templateHandler->render('form/' . $formData[$k]['tiposType'], $formData[$k]);
		}

		return $formData;
	 }
	 
	 /**
	  *  @brief Get metadata from table
	  *  
	  *  @return Array
	  *  
	  */
	 protected function getMetadata()
	 {
		$query = "SHOW FULL COLUMNS FROM {$this->table}";
		$sql = $this->db->dataset($query);	 
		
		return $sql;
	 }
	 
	 
	 /**
	  *  @brief Gets data from table row
	  *  
	  *  @return Array
	  *  
	  */
	 protected function getData()
	 {
		 
		$query = "	SELECT * 
					FROM {$this->table} 
					WHERE id = {$this->id}";
		$sql = $this->db->record($query);

		return $sql;
	 }
	
	/**
	 *  @brief Gets and processes data for given field
	 *  
	 *  @param [in] $data 
	 *  @return Return_DMicedescription
	 *  
	 */
	protected function getFieldData($field, $type, $comment)
	{
		$value = $this->data[$field];
		
		// Enum
		if ($type == 'enum'){
			
			$commentData = explode(':', $comment);
			$aData = explode(',', $commentData[1]);
			
			return $aData;
		}
		
		// Foreign key
		if ($type == 'select'){
			
			$parent = $this->getParent($field);
			$textField = $this->getTextField($this->removePrefix($parent));
			$query = "SELECT id, {$textField} AS nombre FROM {$parent} ORDER BY {$textField}";
			$sql = $this->db->dataset($query);
			return $sql;
		}
		
		// Normal data field
		return $value;		
		
	}
	
	/**
	 *  @brief Adds to ignores array
	 *  
	 *  @param [in] $field
	 *  @return Void
	 *  
	 */
	protected function addToIgnores($field)
	{
		$this->ignores[] = $field;
	}
	 
	/**
	 *  @brief Gets table from foreign key field
	 *  
	 *  @param [in] $field DB field name with _id
	 *  @return Pluralized field name
	 *  
	 */
	protected function getParent($fieldName)
	{
		
		$field = strtolower(substr($fieldName, 0, -3));
		
		$end1 = substr($field, -1); // last char
		$end2 = substr($field, -2); // last 2 chars
		$end3 = substr($field, -3); // last 3 chars
		$end = "s";
		
		if ($end1 == 'y') 	{ $field = substr($field, 0, -1); $end = "ies"; }
		if ($end2 == 'in') 	$end = "es"; 
		if ($end2 == 'ad') 	$end = "es"; 
		if ($end3 == 'ion') 	$end = "es"; 
		if ($end3 == 'ial') 	$end = "es"; 
		if ($end3 == 'tor') 	$end = "es";
		if ($end3 == 'd') 	$end = "es";

		return XNAME . "_" . $field . $end;
	}	
	
	/**
	 *  @brief Gets text field to show on select for given field 
	 *  
	 *  @param [in] $field
	 *  @return String
	 *  
	 */
	protected function getTextField($field)
	{
		$textFieldArray = \Config::getParameter('field_text');
		if (array_key_exists($field, $textFieldArray)){
			return $textFieldArray[$field];
		} else {
			return $textFieldArray['default'];
		}
	}
	
	protected function removePrefix($table)
	{
		return str_replace(XNAME . '_', '', $table);
		
	}
	
}







// End of file