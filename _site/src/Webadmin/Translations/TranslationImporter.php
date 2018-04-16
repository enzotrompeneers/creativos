<?php
/**
 * Translation importer
 *
 * For importing webadmin translations
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Webadmin\Translations;


class TranslationImporter
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
	  *  @brief Imports CSV file to languages, only one language at a time
	  *  
	  *  @param [in] $language Language to import
	  *  @return Void
	  *  
	  */
	 public function import($language)
	 {
		 // Set up file
		$fileExtensions = array('csv'); // FILES
		$fileName 		= $_FILES['file_name']['name'];
		$extension		= pathinfo($fileName, PATHINFO_EXTENSION);
		
		if (!in_array($extension, $fileExtensions)) throw new \Exception('Only CSV files allowed');
			
		$file 			= fopen($_FILES['file_name']['tmp_name'], 'r');
		$importLanguage	= $language;
		

		
		$n = 0;
		while(!feof($file)) {
			$row = fgetcsv($file, 1024, ';');
			
			// printout($aLanguages);

			if ($n==0) {
				
				// Set up what indexes have the content for the selected language
				$aLanguages 		= $row;
				$thisTradIndex 		= array_search($importLanguage, $aLanguages);
				
				$thisArtIndex 		= array_search('art_'.$importLanguage, $aLanguages);
				$thisClaveIndex		= array_search('clave', $aLanguages);
				
			
			} else {
				$aTraducciones =  $row;
				// Update table with content if not empty
				
				$thisTraduccion = (array_key_exists($thisTradIndex, $aTraducciones)) ? $this->prepareText($aTraducciones[$thisTradIndex]) : '';
				
				if ($thisTraduccion != '') {
					$thisClave = $aTraducciones[$thisClaveIndex];
					$tradQuery = "UPDATE ".XNAME."_traducciones SET {$importLanguage} = '{$thisTraduccion}' WHERE clave = '{$thisClave}'";
					// echo 'trad:'.$tradQuery.'<br/>';
					$this->db->query($tradQuery);		
					
				}
				if ($thisArtIndex) {
					$thisArticulo = (array_key_exists($thisArtIndex, $row)) ? $this->prepareText($row[$thisArtIndex]) : '';
					if ($thisArticulo != '' && $thisArticulo != 'NULL') {
						$artQuery = "UPDATE ".XNAME."_traducciones SET art_{$importLanguage} = '{$thisArticulo}' WHERE clave = '{$thisClave}'";
						// echo 'art:'.$artQuery.'<br/>';	
						$this->db->query($artQuery);	
					}
	
					
				}

			}
			
			$n++;
		}
	
		header('Location: ' . \Helpers::getLink('traducciones'));
	 }
	 
	 /**
	  *  @brief Prepares text for import/export
	  *  
	  *  @param [in] $text raw text
	  *  @return prepared text
	  *  
	  */
	 protected function prepareText($text)
	 {
		 return  mysql_real_escape_string(mb_convert_encoding($text, 'UTF-8'));
	 }
	
}

	
	
// End file