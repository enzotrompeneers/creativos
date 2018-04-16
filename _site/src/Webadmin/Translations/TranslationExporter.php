<?php
/**
 * Translation importer
 *
 * For importing webadmin translations
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Webadmin\Translations;


class TranslationExporter
{
	protected $registry = null;
	protected $db = null;
	
	
	/**
	 *  @brief Constructor, sets registry and db properties
	 *  
	 *  @param [in] $db Database class
	 *  
	 */
	 public function __construct (\Brunelencantado\Registry\Registry $registry)
	 {
		 $this->registry = $registry;
		 $this->db = $this->registry->getObject('db');
		
	 }
	
	
	 /**
	  *  @brief Exports whole traducciones table to CVS
	  *  
	  *  @return void
	  *  
	  */
	 public function export()
	 {
		 // Get info from database
		$query = "SELECT * FROM ".XNAME."_traducciones WHERE used = 1";
		$sql = $this->db->dataset($query);

		// Set up 
		header('Content-Type: text/csv; charset=Windows-1252');
		header('Content-Disposition: attachment; filename='.XNAME.'_traducciones_'.date('Y-m-d').'.csv');	
		$output = fopen('php://output', 'w');
		$fields = \Config::getParameter('languages');
		array_unshift($fields, 'clave');

		// Add content to file
		fputs($output, implode($fields, ';') . "\n");
		foreach ($sql as $k=>$v) {
			$aFields = array();
			foreach ($fields as $l) {
				$aFields[] = iconv('UTF-8', 'Windows-1252', $v[$l]);
			}
			// array_shift($fields);
			// foreach ($fields as $l) {
				// $aFields[] = 'art_' . iconv('UTF-8', 'Windows-1252', $v['art_' . $l]);
			// }
			fputs($output, implode($aFields, ';') . "\n");
		}
	 }
	
}

	
	
// End file