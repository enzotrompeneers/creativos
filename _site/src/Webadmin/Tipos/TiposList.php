<?php
/**
 * Tipos - General CRUD manager for CMS
 *
 * Gets a list and metadata
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Webadmin\Tipos;


class TiposList
{
	protected $registry;
	protected $db;
	protected $table;
	protected $folder;
	protected $languages;
	protected $router;
	
	protected $hasImages;
	protected $fields;
	protected $tickFields = array('visible', 'vendido', 'reservado', 'destacado');
	protected $exceptions = array('localidads' => 'localidades');
	protected $options;
	
	 /**
	  *  @brief Brief
	  *  
	  *  @param [in] $table Table name without prefix
	  *  @param [in] $registry To access database
	  *  
	  */
	 public function __construct (\Brunelencantado\Registry\Registry $registry, $table, $router)
	 {
		$this->registry = $registry;
		$this->db = $this->registry->getObject('db');
		$this->router = $router;
		$this->languages = \Brunelencantado\Config::getParameter('languages');
		
		$this->table = $table;
		$this->checkValidTable($table);
		
		$this->folder = $this->getFolder();
		$this->hasImages = $this->hasImages();
		$this->fields = $this->getFields();

	 }	
	 
	 /**
	  *  @brief Creates a list from table data and related tables
	  *  
	  *  @return Array
	  *  
	  */
	 public function getList()
	 {
		$fields			= $this->fields;
		$fieldsQuery 	= 't.' . implode(', t.', $fields);
		$images 		= XNAME . '_images_' . $this->folder;
		$imageQuery 	= null;
		
		// Auxilary table fields
		$foreignQuery = null;
		foreach ($fields as $f){
			if ($this->isForeignKey($f)){
				$parent = $this->getParent($f);
				$nombre = $this->getName($parent);
				$foreignQuery .= " ,(SELECT {$parent}.{$nombre} FROM {$parent} WHERE {$parent}.id = t.{$f}) AS {$f}";
			}
		}
		
		// Images
		if ($this->hasImages) {
			$imageQuery = "
							, (SELECT i.file_name 
							FROM {$images} i
							WHERE i.parent_id = t.id
							ORDER BY i.orden ASC
							LIMIT 1) AS file_name";
		}
		
		// Main query
		$listQuery	=	"
						SELECT t.id, {$fieldsQuery} {$imageQuery} {$foreignQuery}
						FROM {$this->table} t
						GROUP BY t.id
						ORDER BY t.id DESC
						";
		$listSql 	=	$this->db->dataset($listQuery);
		
		return $listSql;
		
	 }
	 
	 /**
	  *  @brief Gets table columns based on list of fields
	  *  
	  *  @return Array
	  *  
	  */
	 public function getColumns()
	 {
		$fields = $this->getFields();
		
		$n = 1;
		$columns = array();
		
		// Id is always first
		array_unshift($columns, array(
			'index' => 0, 
			'label' => 'id', 
			'type' => $this->getColumnType('id'))
			);
		
		// does the table have images?
		if ($this->hasImages()) 
		{
			array_push($columns, array(
				'index' => 1, 
				'label' => 'imagenes', 
				'type' => $this->getColumnType('imagenes'))
				); 
			$n = 2;
		}
 		
		foreach ($fields as $f){
			$columns[$n]['index'] = $n;
			$columns[$n]['label'] = $f;
			$columns[$n]['type'] = $this->getColumnType($f);
			$n++;
		}		
		// printout($columns);
		return $columns;
	 }
	
	 /**
	  *  @brief Gets "folder", that is the table name without prefix
	  *  
	  *  @return String
	  *  
	  */
	 public function getFolder()
	 {
		$table = $this->table;
		$prefix = XNAME . '_';
		return str_replace($prefix, '', $table);
	 }
	 
	 /**
	  *  @brief Adds an exception
	  *  
	  *  @return Void
	  *  
	  */
	 public function addException($original, $fixed)
	 {
		$this->exceptionsArray[$original] = $fixed;
	 }
	 
	 /**
	  *  @brief Adds an options array to set the proper fields to be shown on foreign tables
	  *  
	  *  @return Void
	  *  
	  */
	 public function addOptions(array $options)
	 {
		$this->options = $options;
	 }
	 
	 /**
	  *  @brief Adds a tick field
	  *  
	  *  @return Void
	  *  
	  */
	 public function addTick($field)
	 {
		$this->tickFields[] = $field;
	 }
	 
	 /**
	  *  @brief Gets field list from database or creates defaults
	  *  
	  *  @return Array
	  *  
	  */
	 protected function getFields()
	 {
		$fieldsQuery = "SELECT table_fields FROM ".XNAME."_list_data WHERE table_name = '{$this->table}'";
		$fieldsSql = $this->db->record($fieldsQuery);
		
		if ($fieldsSql['table_fields']){
			$fields = explode(' ', trim($fieldsSql['table_fields']));
		} else {
			$fields = array();
			$n = 0;
			foreach ($this->languages as $l) {
				$fields[] = 'nombre_'.$l;
				$n++;
			}					
		}

		return $fields;
	 }
	 
	 /**
	  *  @brief Returns true if table has images
	  *  
	  *  @return Boolean
	  *  
	  */
	 protected function hasImages()
	 {
		$folder = $this->folder;
		return $this->db->query("SHOW TABLES LIKE '".XNAME."_images_{$folder}'")->num_rows > 0;
	 }
	 
	 /**
	  *  @brief Returns name to show on foreign key
	  *  
	  *  @param [in] $field Foreign field
	  *  @return field to show
	  *  
	  */
	 protected function getName($field)
	 {
		$nombre = $this->options['default'];
		foreach ($this->options as $x => $y) {
			if ($x == str_replace(XNAME . '_', '', $field)) {
				$nombre	= $y;
			}
		}
		
		return $nombre;
	 }
	 
	 /**
	  *  @brief Checks if table exists in database
	  *  
	  *  @param [in] $table Table to test
	  *  @return True or false
	  *  
	  */
	 protected function checkValidTable($table)
	 {
		$query = "SHOW TABLES LIKE '{$table}'";
		$result = $this->db->record($query);
		if (!$result){
			header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
			echo $this->router->render('404');
			exit;
		}
		
	 }
	 
	 /**
	  *  @brief Says if a table is a foreign key or not
	  *  
	  *  @param [in] $table Table to test
	  *  @return Boolean
	  *  
	  */
	 protected function isForeignKey($field)
	 {
		 if(preg_match("/_id$/", $field))  return true;
	 }
	 
	 /**
	  *  @brief Gets type of field based on name
	  *  
	  *  @param [in] $field Field to be tested
	  *  @return string
	  *  
	  */
	 protected function getColumnType($field)
	 {
		// Basic types
		 switch ($field){
	
			 case 'id':
				$type = 'id';
				break;
			 case 'imagenes':
				$type = 'image';
				break;
			 case 'precio_de_venta':
				$type = 'precio';
				break;
				
			 default: 
				$type = 'text';
		 }

		 // Tick type
		 if (in_array($field, $this->tickFields)){
			 $type = 'tick';
		 }
		 
		 return $type;
	 }
	 
	 protected function getParent($field)
	 {
		$parent = pluralize(substr($field, 0, -3));
		 
		// Exceptions to correct automatic mishaps
		foreach ($this->exceptions as $x => $y) {
			$parent = ($parent == XNAME . '_' . $x)? XNAME . '_' . $y : $parent;
		}		
		
		return $parent;
	 }

	 
}







// End of file