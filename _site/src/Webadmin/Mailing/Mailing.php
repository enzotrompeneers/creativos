<?php
/**
 * Tipos - General CRUD manager for CMS
 *
 * Processes URI requests
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Webadmin\Mailing;

use \Brunelencantado\Registry\Registry;

class Mailing
{
	protected $registry = null;
	protected $db = null;
	
	/**
	 *  @brief Constructor, sets registry and db properties
	 *  
	 *  @param [in] $db Database class
	 *  
	 */
	 public function __construct (Registry $registry)
	 {
		 $this->registry = $registry;
		 $this->db = $this->registry->getObject('db');
		
	 }	

	 /**
	  * Gets array of languages for select
	  *
	  * @param Array $languages
	  * @return Array
	  */
	 public function getLanguages(array $languages)
	 {

		$output = [];
		foreach ($languages as $k => $v) {
			
			$output[$k]['id'] = $v;
			$output[$k]['nombre'] = $v;

		}
		
		return $output;

	 }

	 /**
	  * Gets array of viviendas for select
	  *
	  * @param Array $viviendas
	  * @return Array
	  */
	 public function getViviendas(array $viviendas)
	 {

		$output = [];
		foreach ($viviendas as $k => $v) {
			
			$output[$k]['id'] = $v['id'];
			$output[$k]['nombre'] = $v['referencia'] . ' - ' . $v['titulo'];

		}
		
		return $output;
	 }

	 /**
	  * Gets data array form ay table forselect
	  *
	  * @param String $folder
	  * @param String $field
	  * @return Array
	  */
	 public function getData($folder, $field = null)
	 {
		 
		 $fieldSelect = ($field) ? $field : 'nombre_' . LANGUAGE;
		 $query 	= "SELECT id, {$fieldSelect} AS nombre FROM ".XNAME."_{$folder} ORDER BY {$fieldSelect}";
		 $sql 	= $this->db->dataset($query);

		 $choose = array('id'=>'','nombre' => trad('elige')); // Primera opción
		 if (!empty($sql)){
			 array_unshift($sql,$choose);
			 return $sql;
		 } 

		 return false;
	 
	 }

	 /**
	  * Gets number range for select
	  *
	  * @param [type] $min
	  * @param [type] $max
	  * @param integer $step
	  * @return void
	  */
	 public function getRange($min, $max, $step=1) 
	 {
		 $output = array();
		 $n = 1;
		 foreach (range($min, $max, $step) as $number) {
			 $output[$n]['id'] = $number;
			 $output[$n]['nombre'] = number_format($number);
			 $n++;
		 }
		 
		 $choose = array('id' => '', 'nombre' => trad('elige')); // Primera opción
		 array_unshift($output, $choose);

		 return $output;
		 
	 }

	 /**
	  * Gets list of templates for select
	  *
	  * @return Array
	  */
	 public function getTemplates()
	 {

		$query = "SELECT id, asunto_".LANGUAGE." AS nombre FROM ".XNAME."_emails";
		$sql = $this->db->dataset($query);

		$choose = array('id' => '', 'nombre' => trad('elige')); // Primera opción
		array_unshift($sql, $choose);

		return $sql;

	 }
}







// End of file