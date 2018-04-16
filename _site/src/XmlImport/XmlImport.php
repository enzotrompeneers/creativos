<?php
/**
 * xml_import.class.php
 * Copyright (C)2015  Daniel beard <daniel@brunel-encantado.com> 
 * 
 * XML import functions // Default is for Kyero format, any other format must create child
 * 
 */

namespace Brunelencantado\XmlImport;

use Brunelencantado\Logger\Logger;

class XmlImport
{

	protected $url;
	protected $agent;
	protected $localFile;

	public $log;
	public $xml;
	public $db;
	
	public function __construct(array $setup, $db, $log)
	{

		if (empty($setup['url'])) 			throw new \Exception('Missing url variable');
		if (empty($setup['agent'])) 		throw new \Exception('Missing agent variable');
		
		$this->url			= $setup['url'];
		$this->agent		= $setup['agent'];
		
		//Connect to the database
		$this->db = $db;
		
		// Set up log file
		$this->log			= $log;
		$this->localFile	= dirname(__FILE__).'/xml/'.$this->agent.'.xml';

		// Download XML file, save and put into simpleXml object
		if (pREMOTE == true) $this->getUrl();
		$this->loadLocalFile();
		return $this->xml;
	}
	
	/**
	 * Inserts database entry if it doesn't exist, updates if it does.
	 */		
	public function save(array $data, $table)
	{
		$thisId	= $this->entry_exists($data['id_xml']);
		
		if ($thisId) {
			// Update
			$this->db->updateQuery($data, $table, array('id_xml' => $data['id_xml'],' agente' => $this->agent));
			$this->log->write(Logger::INFO, 'Updated id_xml = ' . $data['id_xml']);
			return $thisId;
		} else {
			// Insert
			$data['agente']		= $this->agent;
			$data['visible']	= 1;
			$newId = $this->db->insertQuery($data, $table);
			$this->log->write(Logger::INFO, 'Added id_xml: ' . $data['id_xml']);
			return $newId;
		}
		
	}
	
	 /**
	 * Returns location id, if not exists, creates new location and logs new location
	 */		
	 public function getLocationId($location) {
		global $xname, $languages;
		$exists		= false;
		
		// Check to see if exists in localidades table
		$localidadesQuery	= "SELECT id FROM {$xname}_localidades WHERE nombre = '{$location}'";
		$localidadesSql		= $this->db->query($localidadesQuery);
		
		if (is_object($localidadesSql) && $localidadesSql!='') {
			$localidadesRow		= $localidadesSql->fetch_object();
			if (is_object($localidadesRow)) {
				$currentId 			= $localidadesRow->id;
			}
		} else {
			// Check to see if in new_entries table
			$entriesQuery	= "SELECT converted_id FROM {$xname}_new_entries WHERE entry = '{$location}'";
			$entriesSql		= $this->db->query($entriesQuery);
			
			if ($entriesSql) {
				$entriesRow		= $entriesSql->fetch_object();			
				$currentId 		= $entriesRow->converted_id;
			} 
			
		}
		
		// If it already exists in either localidades or new_entries, we return it, otherwise we enter it into both tables
		if (!empty($currentId)) {
			// Exists, so return id
			return $currentId;
		} else {
			// Add it to table
			$insert	= array();
			foreach ($languages as $l) {
				$insert['nombre'] = $location;
			}
			$newId = $this->db->insertQuery($insert, $xname . '_localidades');
			$this->log->write(Logger::INFO, 'New location: ' . $location);
			
			// Now add to new_entries table
			$insert					= array();
			$insert['tabla']		= $xname.'_localidades';
			$insert['entry']		= $location;
			$insert['new_id']		= $newId;
			$this->db->insertQuery($insert, $xname.'_new_entries');
			return $insert['new_id'];
		}
		 
	 }
	
	 /**
	 * Enters images from array 
	 */	
	 public function addImages($id, array $aImages, $imageLocation = 'remote')
	 {
		global $xname;
		
		// First check to see if images have changed
		
		// Remote xml images
		$aRemoteImages	= array();
		foreach ($aImages as $l) {
			$aRemoteImages[]	= $l['url'];
		}
		
		// Local images
		$localImagesQuery	= "SELECT file_name FROM {$xname}_images_viviendas WHERE parent_id = {$id}";
		$localImagesSql		= $this->db->query($localImagesQuery);
		$aLocalImages = array();
		while ($l = $localImagesSql->fetch_row()) {
			$aLocalImages[] =  $l[0];
		}
		
		$sLocalImages	= serialize($aLocalImages);
		$sRemoteImages	= serialize($aRemoteImages);
		
		// $this->log->write(Logger::INFO,'Images: ' . $sLocalImages . ' - ' . $sRemoteImages);		
		
		// If changed, delete old images and add new ones
		if ($sLocalImages != $sRemoteImages) {
			// First delete all images for that entry
			$delImagesQuery	= "DELETE FROM {$xname}_images_viviendas WHERE parent_id = {$id}";
			$this->db->query($delImagesQuery);
			
			// Add each image to a vivienda
			foreach ($aImages as $k=>$v) {
				
				$insert					= array();
				$insert['parent_id']	= $id;
				$insert['orden']		= $v['orden'];
				$insert['agente']		= $this->agent;
				
				
				if ($imageLocation == 'remote') {
					// Maintain the remote link
					$insert['file_name']	= $v['url'];
					$this->db->insertQuery($insert, $xname.'_images_viviendas');
					
				} else {
					// Download the image and store in local
					$fileName = random_string(10) . '.jpg';
					$insert['file_name'] = $fileName;
					$this->db->insertQuery($insert, $xname.'_images_viviendas');
					
				}
				
				
			}
			$this->log->write(Logger::INFO,'Adding images to id: '.$id);
	
		}
		
	 }
	 
	/**
	 *  @brief Downloads and resizes images
	 *  
	 *  @details Details
	 */
	public function downloadImage($origen, $destination, $fileName)
	{

		global $config;
		$imagesConfig = $config->get('images');

		if(!file_exists($destination)) { (mkdir($destination, 0755));};
		
		// $remoteFile = $this->getFile($origen);
		pictureresize($origen, $destination."s_".$fileName, $imagesConfig['s'], $imagesConfig['s'] * 1.5);
		pictureresize($origen, $destination."m_".$fileName, $imagesConfig['m'], $imagesConfig['m'] * 1.5);
		pictureresize($origen, $destination."l_".$fileName, $imagesConfig['l'], $imagesConfig['l'] * 1.5);
		pictureresize($origen, $destination."g_".$fileName, $imagesConfig['g'], $imagesConfig['g'] * 1.5);
		
	}	 



	public function convertId(array $idConversion, $id)
	{
		
		if (isset($idConversion[$id])) return $idConversion[$id];

	}

	 /**
	  *  @brief Gets file via Curl
	  *
	  */		
	 protected function getFile($url)
	 {
		 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // good edit, thanks!
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1); // also, this seems wise considering output is image.
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	 }	 
	 
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
	 * Gets remote url and saves to localFile
	 */		
	protected function getUrl()
	{
		$xmlData = file_get_contents($this->url);
		if (file_put_contents($this->localFile, $xmlData)) {
			$this->log->write(Logger::SUCCESS,'Saved '.$this->localFile);
		}
		
		/* //CURL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url);
		$this->log->write(Logger::SUCCESS,'Downloaded '.$this->url);
		$fp = fopen($this->localFile, 'w');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_exec ($ch);
		curl_close ($ch);
		fclose($fp);
		$this->log->write(Logger::SUCCESS,'Saved '.$this->localFile);
		*/
	}
	
	/**
	 *  @brief Loads local XML file
	 *  
	 *  @return void
	 *  
	 */
	protected function loadLocalFile()
	{
		$this->xml = simplexml_load_file($this->localFile,'SimpleXMLElement');
		$this->log->write(Logger::SUCCESS,'Loaded: ' . $this->localFile);
	}
	
	 /**
	 * Does the entry exist in the database?
	 */	
	protected function entry_exists ($idXml)
	{
		global $xname;
		$query	= "SELECT id,id_xml FROM {$xname}_viviendas WHERE id_xml = {$idXml}";
		$sql	= $this->db->query($query);
		if ($sql) {
			$row	= $sql->fetch_object();
			if (!empty($row->id_xml)) {
				return $row->id;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	// Resizes images depending on parameters entered
	protected function pictureresize($source, $dest, $MAXWIDTH, $MAXHEIGHT){
		// Image type
		$format = pathinfo($dest, PATHINFO_EXTENSION);
		if ($format=='png') {
			$p_source=imagecreatefrompng($source);
		} else {
			$p_source=imagecreatefromjpeg($source);
		}
		list($width, $height) = getimagesize($source);
		$scale=min($MAXWIDTH/$width, $MAXHEIGHT/$height);
		$new_width=floor($width*$scale);
		$new_height=floor($height*$scale);
		$p_img = imagecreatetruecolor($new_width,$new_height);
		imagecopyresampled($p_img, $p_source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		if(imagejpeg($p_img, $dest)) echo "";
	}
	

}







// End of file