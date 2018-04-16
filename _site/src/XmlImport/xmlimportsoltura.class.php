<?php
/**
 * xml_import.class.php
 * Copyright (C)2015  Daniel beard <daniel@brunel-encantado.com> 
 * 
 * XML import functions // Default is for Kyero format, any other format must create child
 * 
 */

require_once dirname(__FILE__) . '/../helpers/databasehelpers.class.php';



class XmlImportSoltura extends XmlImport
{

	
	 
	 /**
	 * Deletes database entries that are not in the XML
	 */	
	 public function deleteEntries(){
		 global $xname;
		 
		 
		 // First we get an array of the XML property xml_id's
		 $xmlIdXmlArray = array();
		 foreach ($this->xml->property as $vivienda){
			 $xmlIdXmlArray[] = (string) $vivienda->id;
		 }
		 
		 $databaseIdXmlQuery = "SELECT id_xml FROM {$xname}_viviendas WHERE agente = '{$this->agent}'";
		 $databaseIdSql	 = $this->query($databaseIdXmlQuery);
		 $databaseIdXmlArray = array();
		 while ($databaseIdXml = $databaseIdSql->fetch_row()) {
			 $databaseIdXmlArray[]	=  $databaseIdXml[0];
			 
		 }
			
		 // And we check to see if the database entry is in the XML file, if not, we delete it
		 foreach ($databaseIdXmlArray as $databaseIdXml){
			 if (!in_array($databaseIdXml,$xmlIdXmlArray)) {
				 $this->log->write(Logger::WARNING,'Deleting: '.$databaseIdXml);
				 // Delete entry
				 $deleteQuery = "DELETE FROM {$xname}_viviendas WHERE id_xml = '{$databaseIdXml}'";
				 $this->query($deleteQuery);
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
			foreach ($vivienda->features->feature as $feature) {
				$feature = (string) $feature['name']; // Turn object to string to compare
				if (!in_array($feature,$aFeatures)) {
					$aFeatures[] = $feature;
				}
			}
		}
		$features	= implode(', ',$aFeatures).'.';
		$this->log->write(Logger::INFO,'Features: '.$features);
	}	
	
	 
	 /**
	 * Shows unique property types
	 */	
	public function showUniquePropertyTypes ()
	{
		$propertyTypes	= array();
		foreach ($this->xml->property as $vivienda) {
			// printout($vivienda);
			$type	= (string) $vivienda->type->en; // Turn object to string to compare
			if (!in_array($type,$propertyTypes)) {
				$propertyTypes[]	= $type;
			}
		}
		$types	= implode(', ',$propertyTypes).'.';
		$this->log->write(Logger::INFO,'Property types: '.$types);
	}
	
	
	


	
}







// End of file