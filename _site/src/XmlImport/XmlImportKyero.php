<?php
/**
 * Import Kyero format XML feed
 * Copyright (C)2016  Daniel beard <daniel@brunel-encantado.com> 
 * 
 * XML import functions // Default is for Kyero format, any other format must create child
 * 
 */

namespace Brunelencantado\XmlImport;
 
use \Brunelencantado\Logger\Logger;


class XmlImportKyero extends XmlImport
{

	
	 public $version = '3';
	 
	 public function setVersion ($version)
	 {
		 $this->version = $version;
	 }
	 
	 public function getVersion ($version)
	 {
		 return $this->version;
	 }
	 
	 /**
	 * Deletes database entries that are not in the XML
	 */	
	 public function deleteEntries(){
		 global $xname;
		 
		 
		 // First we get an array of the XML property xml_id's
		 $xmlIdXml = array();
		 foreach ($this->xml->property as $vivienda){
			 $xmlIdXml[] = (string) $vivienda->id;
		 }

		 // Database array of xml_id
		 $databaseIdXmlQuery = "SELECT id_xml FROM {$xname}_viviendas WHERE agente = '{$this->agent}'";
		 $databaseIdXmlSql = $this->db->dataset($databaseIdXmlQuery);
		 $databaseIdXml = array_map(function($a) {  return array_pop($a); }, $databaseIdXmlSql);
		
		 // And we check to see if the database entry is in the XML file, if not, we delete it
		 foreach ($databaseIdXml as $idXml){
			 if (!in_array($idXml, $xmlIdXml)) {
				 $this->log->write(Logger::WARNING, 'Deleting: ' . $idXml);
				 // Delete entry
				 $deleteQuery = "DELETE FROM {$xname}_viviendas WHERE id_xml = '{$idXml}'";
				 $this->db->query($deleteQuery);
			 }
		 }
		

		 
		 
	 }
	 
	 /**
	 * Shows unique features
	 */	
	public function showUniqueFeatures ()
	{
		$aFeatures	= array();
		
		foreach ($this->xml->property as $vivienda) {
			if ($vivienda->features) {
				foreach ($vivienda->features->feature as $feature) {
					$feature = (string) $feature; // Turn object to string to compare
					if (!in_array($feature, $aFeatures)) {
						$aFeatures[] = $feature;
					}
				}				
			}

		}
		sort($aFeatures);
		$features	= implode(', ', $aFeatures) . '.';
		$this->log->write(Logger::INFO,'Features: ' . $features);
	}	
	
	
	 /**
	 * Shows unique property types
	 */	
	public function showUniquePropertyTypes ()
	{
		$propertyTypes	= array();
		$propertyCount	= array();
		foreach ($this->xml->property as $vivienda) {
			$type	= (pVERSION=='2_1')?(string) $vivienda->type->en:(string) $vivienda->type; // Turn object to string to compare
			if (empty($propertyCount[$type])) {
				$propertyCount[$type] = 1;
			} else {
				$propertyCount[$type]++;
			}
		}
		
		// Format output
		$output = '';
		foreach ($propertyCount as $k=>$v) {
			$output .= $k.' ('.$v.'), ';
		}
		rtrim($output,',').
		$output .= '.';
		
		$this->log->write(Logger::INFO,'Property types: '.$output);
	}
	
	/**
	 *  @brief Function to set the next property to download
	 *  
	 *  @param [in] $n Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	public function setNextCount($n)
	{
		global $xname;
		$this->db->updateQuery(array('valor' => $n), $xname . '_config', array('clave' => 'nextCount'));
	}
	
	public function nextCount()
	{
		global $xname;
		$query = "SELECT valor FROM {$xname}_config WHERE clave = 'nextCount'";
		$sql = $this->db->record($query);
		
		return (int) $sql['valor'];
	}
	
}







// End of file